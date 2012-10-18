<?php

/**
 * This is the model class for table "{{events}}".
 *
 * The followings are the available columns in table '{{events}}':
 * @property string $id
 * @property string $title
 * @property string $description
 * @property string $datetime
 * @property string $logo
 */
class Events extends CActiveRecord
{
	// Все возможные статусы Мероприятия
	public static $STATUS = array(
			'draft'=>'Черновик',
			'published'=>'Опубликован',
		);

	public $delete_logo;	//переменная, нужная чтобы узнать удаляет ли пользователь логотип.
	public $date;			//Дата мероприятия
	public $time;			//Время мероприятия
	public $addEventFacebook = false;//Добавить ли создаваемое событие в Facebook
	public $online = false;//Будет ли доступно событие online.

	/**
	 * This method is invoked before saving a record (after validation, if any).
	 * The default implementation raises the {@link onBeforeSave} event.
	 * You may override this method to do any preparation work for record saving.
	 * Use {@link isNewRecord} to determine whether the saving is
	 * for inserting or updating record.
	 * Make sure you call the parent implementation so that the event is raised properly.
	 * @return boolean whether the saving should be executed. Defaults to true.
	 */
	protected function beforeSave()
	{
		if(parent::beforeSave())
		{
			if($this->isNewRecord)
			{
				$RSA = new RSA();
				$keys = $RSA->generate_keys ($this->get_prime(), $this->get_prime(), 0);	//2 простых числа и коэф. для отладки

				$this->general_key=$keys[0];
				$this->open_key=$keys[1];
				$this->close_key=$keys[2];
				$this->author = Yii::app()->user->id;
				$this->logo='/images/logo/default.png';
			}
			$this->datetime = Yii::app()->mf->dateForMysql($this->date).' '.$this->time. ':00';

			if (empty($this->facebook_eid)) $this->facebook_eid = null;
				//Удаление логотипа
			if ($this->delete_logo==1 && $this->logo!='/images/logo/default.png')
			{
				@unlink('.' .$this->logo);
				@unlink('.' .$this->changeNameImageOnMini($this->logo));
				$this->logo='/images/logo/default.png';
			}
			elseif ($_FILES['Events']['tmp_name']['logo']!='')			//Если пользователь приложил логотип
			{
				$filename = $_FILES['Events']['name']['logo'];
				$filesize = $_FILES['Events']['size']['logo'];
				$valid_images = array('gif','jpg','png','jpeg');
				$ext = strtolower(substr($filename, 1+strrpos($filename, '.')));	//получили расширение файла
				if ($filesize < 1048576 && in_array($ext, $valid_images))
				{
					$this->logo='/images/logo/'.$this->id.'.'.$ext;
					Yii::app()->ih
						->load($_FILES['Events']['tmp_name']['logo'])
						->thumb(300,false)
						->save(Yii::app()->basePath.'/../images/logo/'.$this->id.'.'.$ext, false, 80)
						->thumb(173,false)
						->save(Yii::app()->basePath.'/../images/logo/'.$this->id.'_mini.'.$ext, false, 80);
				}
				else{
                                    $this->addError('logo', 'Картинка должна иметь размер не больше 1 Мб.');
					return false;
                                }
			}


			return true;
		}
		else
			return false;
	}

	/**
	 * This method is invoked after saving a record successfully.
	 * The default implementation raises the {@link onAfterSave} event.
	 * You may override this method to do postprocessing after record saving.
	 * Make sure you call the parent implementation so that the event is raised properly.
	 * Событие уже создано - нужно создать его на facebook'e.
	 */
	protected function afterSave()
	{
            $this->uniq = md5($this->id.time());

            //и qr код
            include_once("./phpqrcode/qrlib.php");
            $errorCorrectionLevel = 'L';
            $matrixPointSize = 3;
            $data = 'apikey:'.$this->uniq;

            $filename = $this->id. '_' .sprintf('%x',crc32($this->id.time())). '.png';
            $filepath = '.' .DIRECTORY_SEPARATOR. 'images' .DIRECTORY_SEPARATOR. 'qrcode' .DIRECTORY_SEPARATOR.$filename;
            $this->qr = '/images/qrcode/' .$filename;
            QRcode::png($data, $filepath, $errorCorrectionLevel, $matrixPointSize, 2);

            Events::model()->updateByPk($this->id, array('uniq'=>$this->uniq, 'qr'=>$this->qr));

            if($this->addEventFacebook && !$this->facebook_eid){
                if (!Yii::app()->user->access_token || !$this->addEventFacebook(Yii::app()->user->access_token, $this->id)){
                        $auth_url = "https://www.facebook.com/dialog/oauth?client_id=".Yii::app()->params['face_id']."&redirect_uri=http://". $_SERVER['HTTP_HOST']."/events/view/".$this->id."&scope=create_event";
                        header('Location: '.$auth_url);
                        die();
                }
            }
	}

        protected function afterDelete()
        {
            parent::afterDelete();
            Tickets::model()->deleteAll('event_id="'.$this->id.'"');
            TransactionLog::model()->deleteAll('event_id="'.$this->id.'"');
        }

        public function addEventFacebook($access_token, $id){
            $data = array();
            $ticket = Tickets::model()->findAll("event_id = :event_id", array(":event_id" => $id));
            $typing = $ticket[0]->attributes['type'];
            if ($typing=='travel'){
	                $bdate = strtotime( $ticket[0]->attributes['date_begin'] );
	                $edate = strtotime( $ticket[0]->attributes['date_end'] );
	                $start = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m",$bdate), date("d",$bdate), date("y",$bdate)));
	                $ending = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m",$edate), date("d",$edate), date("y",$edate)));
	        }{
	                $end_time = $ticket[0]->attributes['time_end'];
	                $ndate = strtotime( $this->datetime );
	                $start = date('Y-m-d H:i:s', $ndate);
	                if($end_time){
						$end_time = strtotime($end_time);
						$ending = date("Y-m-d H:i:s",mktime(date("H",$end_time), date("i",$end_time), 0, date("m",$ndate), date("d",$ndate), date("y",$ndate)));
					}else{
						$ending = date("Y-m-d H:i:s",$ndate + 3600 * 24);
					}
	        }

	        $data['access_token'] = $access_token;
	        $data['name'] = $this->title;
	        $data['description'] = $this->description;
	        $data['location'] = 'Showcode.ru';
	        $data['street'] = $model->address;
	        $data['city'] = 'Москва';
			$data['country'] = 'Россия';
	        $data['privacy_type'] = 'OPEN'; # OPEN, CLOSED, SECRET
	        $data['start_time'] = $start; # timezone info is stripped
	        $data['end_time'] = $ending;
	        $data['source'] = '@'.realpath('.'.$this->logo);

	        $url = "https://graph.facebook.com/me/events";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $decoded = json_decode($result, true);
            curl_close($ch);

            if(is_array($decoded) && isset($decoded['id'])) {
                Events::model()->updateByPk($this->id,array('facebook_eid'=>$decoded['id']));
                return $decoded['id'];
            }else{
            	return null;
            }
        }

	/**
	 * Returns the static model of the specified AR class.
	 * @return Events the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{events}}';
	}

	/**
	 * This method is invoked before validation starts.
	 * The default implementation calls {@link onBeforeValidate} to raise an event.
	 * You may override this method to do preliminary checks before validation.
	 * Make sure the parent implementation is invoked so that the event can be raised.
	 * @return boolean whether validation should be executed. Defaults to true.
	 * If false is returned, the validation will stop and the model is considered invalid.
	*/
	protected function beforeValidate()
	{
		if (!preg_match("/^([0-1][0-9]|[2][0-3]):([0-5][0-9])$/", $this->time))
			$this->addError('time','Время имеет не правильный формат');
		return true;
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, description, status, date, time,address', 'required', 'message'=>'Не может быть пустым'),
			array('column, place, facebook_eid', 'numerical', 'integerOnly'=>true, 'message'=>'Вводите только числа'),
			array('column, place', 'length', 'max'=>3),
			array('addEventFacebook', 'boolean'),
			array('online', 'boolean'),
			array('title, date, time, facebook_eid', 'length', 'max'=>50),
			/*array('logo', 'file',
				'types'=>'jpg, gif, png, ',
                'maxSize'=>1024 * 1280 * 2, 	// 2MB
                'tooLarge'=>'Изображение должно быть не больше 1024 * 1280 точек и не больше 2 МБ',
				),*/
			// По следующим атрибутам осуществляется поиск:
			array('title, description, datetime, author, status, logo, facebook_eid,address', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
                    'uniqium'=>array(self::HAS_ONE, 'EventUniq', 'event_id'),
                    'tickets'=>array(self::HAS_MANY, 'Tickets', 'event_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'facebook_eid' => 'Встреча в Facebook',
			'title' => 'Название',
			'uniq' => 'Уникальный ключ',
			'description' => 'Описание',
                        'address' => 'Адрес',
			'datetime' => 'Дата и время',
			'date' => 'Дата',
			'time' => 'Время',
			'author' => 'Автор',
			'status' => 'Статус',
			'logo' => 'Изображение',
			'column' => 'Ряд',
			'place' => 'Место',
			'open_key' => 'Открытый ключ',
			'close_key' => 'Закрытый ключ',
			'general_key' => 'Общий ключ',
			'delete_logo' => 'Удалить логотип',
			'addEventFacebook' => 'Добавить событие в Facebook',
			'online' => 'Будет доступна online',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('facebook_eid',$this->facebook_eid,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('datetime',$this->datetime,true);
		if (Yii::app()->user->isAdmin())
			$criteria->compare('author',$this->author,true);
		else
			$criteria->compare('author',Yii::app()->user->id,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('logo',$this->logo,true);
                $criteria->order = 'datetime DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @param string $event_id id мероприятия.
	 * @return string название мероприятия.
	 */
	public function getEventTitle($event_id)
	{
		$event = Events::model()->findByPk($event_id, array('select' => 'title'));
		return $event->title;
	}

	/**
	 * @param string $event_id id мероприятия.
	 * @return string дату мероприятия.
	 */
	public function getEventDate($event_id)
	{
		$event = Events::model()->findByPk($event_id, array('select' => 'datetime'));
		list($date,$time) = explode(' ',$event->datetime);
		list($y,$m,$d) = explode('-',$date);

		if (isset($d))
		{
			return $d.'.'.$m.'.'.$y;
		}
		else
			return $event->datetime;
	}

        /**
	 * @param string $event_id id мероприятия.
	 * @return string дату мероприятия.
	 */
	public function getEventTime($event_id)
	{
		$event = Events::model()->findByPk($event_id, array('select' => 'datetime'));
		list($date,$time) = explode(' ',$event->datetime);

		if (isset($time)){
                        list($h,$min,$sec) = explode(':',$time);
                        return $h.':'.$min;
                }
                else
                        return 'В любое время';
	}


	/**
	 * Изменяет путь до изображения для вывода более маленькой превьюшки.
	 * @param string $name путь до изображения
	 * @return string путь до уменьшенного изображения
	 */
	public function changeNameImageOnMini($name)
	{
		preg_match("/^([\s\S]*)\.([\w]*)$/i", $name, $temp);
        return $temp[1]. '_mini.' .$temp[2];
	}

	/**
	 * Функция преобразовывает дату в ЧеловекоПонятный формат.
	 * Сама определяет дата со временем или нет.
	 * @param string $datetime дата формата mysql.
	 * @return string ЧеловекоПонятная дата.
	 */
	public function normalViewDate($datetime)
	{

		list($date,$time) = explode(' ',$datetime);
		list($y,$m,$d) = explode('-',$date);
		if (isset($d))
		{
			if (isset($time))
			{
				list($h,$min,$sec) = explode(':',$time);
				return $d.'.'.$m.'.'.$y.' '.$h.':'.$min;
			}
			else
				return $d.'.'.$m.'.'.$y;
		}
		else
			return $datetime;

	}

	/**
	 * Функция генерирует код для вставки ссылки мероприятия на сторонние ресурсы.
	 * @return string сгенерированный код.
	 */
	public function getHtmlCode()
	{
		//$HtmlCode = '<script type="text/javascript">function openMenu(){document.getElementById("payment").style.display = "block";document.getElementById("button_bye").style.display = "none";};function closeMenu(){document.getElementById("payment").style.display = "none";document.getElementById("button_bye").style.display = "block";return false;};</script>';
		$HtmlCode = '<script type="text/javascript">function openMenu(){document.getElementById("payment").style.display = "block";};function closeMenu(){document.getElementById("payment").style.display = "none";return false;};</script>';
		//$HtmlCode.= '<a href="http://' .$_SERVER['HTTP_HOST']. '/events/view/' .$this->id. '" > <img src="http://' .$_SERVER['HTTP_HOST'].$this->changeNameImageOnMini($this->logo). '"></a><br><a href="http://' .$_SERVER['HTTP_HOST']. '/events/view/' .$this->id. '">' .$this->title. '</a>';
                /*if($ticket[0]['type'] == 'free')
                    CHtml::link('Пойду', '#',array('class' => 'buy_ticket_button', 'id'=>'button_bye'));
                else
                    CHtml::link('Купить билет', '#',array('class' => 'buy_ticket_button', 'id'=>'button_bye'));*/

		$HtmlCode.= '<a style="background: url(http://' .$_SERVER['HTTP_HOST']. '/images/button_buy_ticket.png) no-repeat scroll left top transparent; color: #FFFFFF; cursor: pointer; display: block; font-size: 13px; height: 41px; line-height: 20px; margin: 4px auto; padding-bottom: 2px; position: relative; text-align: center; text-decoration: none; text-shadow: 0 1px 1px #565656; vertical-align: middle; width: 180px;" id="button_bye" href="#" onClick="openMenu();return false;"></a>';

                $HtmlCode.= '<div id="payment" style="border: medium none;box-shadow: 0 0 30px -5px #000000;display: none;height: 522px;left: 50%;margin-left: -408px;margin-top: -267px;padding: 3px;position: fixed;top: 50%;width: 784px; z-index: 65010;">';
                $HtmlCode.= '<input type="button" value="" onclick="javascript:closeMenu();return false;" id="buy_close" style="width: 17px;border: none; background:url(http://' .$_SERVER['HTTP_HOST']. '/images/close_button.png) left top no-repeat; display: block; left: 740px; top: 20px; position: absolute; cursor: pointer;" />';
                $HtmlCode.= '<iframe src="http://' .$_SERVER['HTTP_HOST']. '/events/iframe/' .$this->id. '" width="100%" height="100%" style="border:none;"></iframe>';
                $HtmlCode.= '</div>';
		return $HtmlCode;
	}

	/**
	 * Функция получает случайное простое число из диапазона путём рандомного выбора из уже сгенерированных простых чисел.
	 * @return string простое число.
	 */
	public function get_prime()
	{
		$prime[]='1254570178177754169936793462604001497556399420016781524680948266492486550914527261792708570170439156503521044045802690094604471';
		$prime[]='1775077917970936653245937572539284579035269563077722204748751155606528096310635885074717492773716145993156966927166860757139767';
		$prime[]='1265643079468785999793017784678008370274010750400435580378807055811877010379773838915204484114383119374348060606154439075070819';
		$prime[]='1299836168979214454320561736881582202818465461840711691407563795836042199591350439164052778044111591577011129920998682435667713';
		$prime[]='1561058235189012869132117655037771065784551421159779384905720488825719304489716436979983309080964172404804021543496171420855123';
		$prime[]='1504213046624175363089522266062981030935750346485654203647781432276895882715978163646057915087605491625791268093975220710976267';
		$prime[]='1099125358992508597037667173886207826631924646448090497709333188083819869353894557163619605418455029901932248465295268379167383';
		$prime[]='1529101243330895666804930863071140171186419480530517578024288641588835934084079117866251092196548997687104774406687168009494221';
		$prime[]='1033546377844917459012114978948588691221892064778289917355600197559942259121915815705089633848572143673745171028516811249520403';
		$prime[]='1062861210518916428912390753724885643789835067106760412464691869302450223672196966871829184046621951896880353291591821626910989';
		$prime[]='1105927077116855492214923172298833158306828607626909427745417980941137276219549434693904512524565293631270196533178166029477517';
		$prime[]='1550569846167922894799653074466133688027487603250514234726572610151179118881292711405998364747541813526501748835777181194732657';
		$prime[]='1550235239263789557351860100053728085147999819445539229762156655338225582174730199147301390346614859556827366204146677678706097';
		$prime[]='1862546829899581034057974979820849918999325096414323571309075805789513293067079040114218233084736469412421232154364400315339777';
		$prime[]='1341167835109280778461121331993798230726514881916890651261204045842566960108793233352726238185769090199018418723524477057331173';
		$prime[]='1895152223514063787046740757881197486429017427707577321795621940532135496062048399384849387086386189127049149056151143356702479';
		$prime[]='1850678629318712902965766357778473401268926046182324103780189288222987591530589591123668923944035970411891496043148439789553523';
		$prime[]='1137027107623996965579448397924340727517895042370110372585382109985334122764904877184494617023852596912355922645815687731488923';
		$prime[]='1729704777121326171124959717052962590929978088584029323017432959576699062976052193217594651021795161724785273560273410500768437';
		$prime[]='1882013189124373144290813025658051478943550667861921361660287136147737601044934244875394321955240590712457871551723078814723627';
		$prime[]='1078339982341107569831755413103377121110311893923668100051940659178309292822098793426411551286758415411958428699038047307314149';
		$prime[]='1892462335642539759158089557980108262600013804284594577123722629356660631901562012240135732133699718448287053338257674405816801';
		$prime[]='1594861677126255087646574398846632559930085720864472884278196940096986487777525443680736593766551092174962890955389577210194717';
		$prime[]='1127760219226863201518869987689887553750575994860599437304469559621216494916430499364210132483942880097129047504409413417603603';
		$prime[]='1246744215686747111746797021347183011864875941980709905895166156104993999851935703159565856900668505372021940444178657000865983';
		$prime[]='1969993574160954852366668008172310835801411301223171448260794763699711625870872412672389755865370176446376533700329107442873401';
		$prime[]='1484803842690201827139273536595891422038839136804479309795043187933960031024912016097074060900023583801809065988267278366906383';
		$prime[]='1401354090637835982155032284034012007379226132949570682579106219278784055199543861119716367680084180436648185629193409043899287';
		$prime[]='1093096290181414554320814334282470614953738006161945137011319056082820600279331086062476855757670513457900339518286806014738051';
		$prime[]='1788664310656717035102346224263230434695163525446359455603052844460735617484905044481368211191804224972771766320870094632620431';
		$prime[]='1269802777583643171715304750531869352386856731086189478776485683703369982691717340306501020584856004763858765797358149802499891';
		$prime[]='1777350952682051540002632387381262234504187527388870098371650265567316170091483777624894331592209258031484551055262755978688849';
		$prime[]='1709374928725751862872034289359643734097002269707873587758340312974934328034102310872687253488790481319520417948749474148391939';
		$prime[]='1444284912526861688939292639534100212259072567807304672220443429282941256039797708498219820245397846064745928696242994343202859';
		$prime[]='1365871542383250177492536995776779341541098701568512870093994961290514598898329941934683189167641323578316505121920886521972353';
		$prime[]='1193264185681434141582584745010695614322820625616094602733891594189995002951792798183626432733118404156068037697340791979689847';
		$prime[]='1372796926103201104748134479306677297623572552259719653600250153565536147934999963653195537973978007314924833479665825031432953';
		$prime[]='1671213827034877003939761097481309372332585559749945832723117943048166163186764260651084688045784705589522315741932545745269213';
		$prime[]='1227275805923540026294050510474409064777848462127152133494053399946593103742612809806832019427594967295925112760906900290583377';
		$prime[]='1369734164797827802215895324063892721960841802248461565347396390775266037967393414002411093188470943765734672832611955399173891';
		$prime[]='1529336401965626273849061479681135695914773594268631263794163889961502629628072882882272470381114804673758327384850587124548361';
		$prime[]='1214732537405575220563621371647696550826943567767329267187259689523171584191599647868084446969493991721972857371116483240369799';
		$prime[]='1919578751629099877292918160476689070760907520766147649743031762174680494886762593483973113971214569781899090182393446860133227';
		$prime[]='1795076907166808761875128819393978188410559164245692747883909477588807714699999176576647776027979215562740393026134705780589723';
		$prime[]='1436482972114067266181993044367021250634183775700564307545490255049166369683773845263366691842942982662456201975769939177281063';
		$prime[]='1146979887607181075753361029753873283343111426602240037587375427985189611518522815977559358322084691356459326334263101193325273';
		$prime[]='1827930732813009967835682268321721581695655052230473971250698426367180138102598268389444034624613341784757613065743851914079897';
		$prime[]='1277782480363403471452483673761353444121412128324747984331040557178384069972633548159323228225558554274668495967064862983573851';
		$prime[]='1725317688797321650500509305931331815110570471269854230472170102884579084231802870965236165291421726895539421252848260830285321';
		$prime[]='1672308635544435248817471006247063294836571759376631052017024991987993611635918627887782145663608977788668506316343978924447789';
		$prime[]='1054070553163116855567165871212590673768013766693773643825935589919634441628843824593992386755594146097490265594993961492208679';
		$prime[]='1037183400060290025230851077200406227025751825550884766709850593162635346654592880901701938294374060287869407903863741103419891';
		$prime[]='1502103501503347393187538146273678411396283452256438334799313688822989221809583218571244485053663997993456701864592475997879303';
		$prime[]='1930749382964155711436315639473216165263663106215322889264525188720056995575725782272133038611757813716243693147206736492170521';
		$prime[]='1780989715043849054102240319568849653453998616064792988972127678554970997713148841316540578017149790048767747302646554957966543';
		$prime[]='1412025920015106891118895752123238392761577422677916301313238497132576521959340847092666270083219585444210977711032564514061473';
		$prime[]='1631039193420062159479785257375155333482990746500401344202370688397559526236878320358619614345679918081146029635513893143265461';
		$prime[]='1148104541702622127373458321838323451308589352221286025363257995456103436988112200676171390050144829622737869144755758338137393';
		$prime[]='1918067292156162346709964374835349979331444853900020254082303888144315342076179160755477877352858932064794701559521776113685033';
		$prime[]='1374042715215864217520924120627106854820318705507386257599865987094846435185099896133785614484288887244706391569722484253053783';
		$prime[]='1097832085333360612813990300421109715109866614837100412665448080489738250430331247540136808072504663353295323385852176709533319';
		$prime[]='1959394044332945788305060065904678559188427754846403464743762681637870208054577409201930023180257819322674714568539487113203461';
		$prime[]='1824172213445031871203749168404045194387518542438246111720457121139968447264101616482694694906177401546672861583788642997510357';
		$prime[]='1175440963177946420502504515419013527460900923493925821533494158037684855721307611557676113500613353198470737901343039216708243';
		$prime[]='1713913085711218035466912658560900823362560576157643937711278310848015019252273943467010121449347504331425286586398118742888493';
		$prime[]='1820158319005786912310546085144123576249136384877869342489699762565060755389458810946819656788719047000746533510879210694239209';
		$prime[]='1779760041875221485512469109598605002314477820397161636744879878029245391050779326331518083594812318742490109227643944086726133';
		$prime[]='1516172091546713004161257056870336946529070999257676956261772518801470871865381652564021062521776647756428616904526633595521359';
		$prime[]='1669861935412705165248594030772992152091321041715785170851781364740932277019074459526573019904139308318638903014398573378967431';
		$prime[]='1610676722078936249091861008480529902853319349331964626140728823779783426747058347818701788887433345423957825206328622265299287';
		$prime[]='1612604531128705266816693733597360402265699690977032984148901281404195186791767010310459286976921353470688903773857048391771221';
		$prime[]='1040775482445503865839188644141039644011686435291686221661412598124097791514289125394468683401150741612149674625754394114173807';
		$prime[]='1454080247293219178840492085426659141549544795591661823278648191151818328381246175688456931654066154258436472892657002524037931';
		$prime[]='1247075924229692834424072283924697481150982599813829578815789909487995840609453056421358534187543717520488254864869157195279043';
		$prime[]='1590404781214237880764458643685961092774029618554836773659778218768489478047537219513363658484387299392331907667585492076345517';
		$prime[]='1864257918949256754758548833876167175017345090541172083113552316231837137703851246129669722476523866355669641453614627459080483';
		$prime[]='1423574411118012937220200306056647915888443538045727292865469166313402278630100641686621746485174826573848839363379728795105433';
		$prime[]='1028220281343565748791268594225072827770972679279456616938854996835625079997096346839034659675110002307099705234981324421194137';
		$prime[]='1186447373649027643095325824310096794885119796463408317845797774058152817865151414985896608779287604965311514825494929490178793';
		$prime[]='1628564515423609559694151446004550543971976422327921160354291902393339497995843801063773573438017510480563871474752514465516163';
		$prime[]='1205048847247880250487675735498131937373032427013707370907259617297613823491663157372045326668135121810191267270910394148474781';
		$prime[]='1043712161260306276718014678977793057811444594664613130584101345500703193201737442304255939086883135656280970141331433490382721';
		$prime[]='1763152823040833672003505466155333739547860563625210377194553375717330583825410849797579864966927748954763165310874271073474503';
		$prime[]='1166739959530953084386324248462157002530125764535330252431379338584877602596172832934097262153261420427140812166548603627794309';
		$prime[]='1512594626014399647786464926366151812851115905661449555305965706937817047960289915409875976501675371111000247847639619649816783';
		$prime[]='1640430061631404782487000980002501518182309044207390887774948839899088099619315603003858743806207305280977950478967860059669637';
		$prime[]='1146203920842262252132712404321720245539422351978531812472085260776645782133587158555394674606403700105212760980619582877978763';
		$prime[]='1552076520209111012800456321285021292421132660059445286011051985583682306270237491699727619518655782040609822294283694202017373';
		return $prime[rand(0,87)];
	}

        /**
	 * Возвращает верстку текста для электронной почты при отправке списка билетов.
	 */
	public function getTextEmailSendListTickets($tickets){
            $text = '';
            $text = $text.'<table cellspasing="0" border="0" cellpadding="0" width="697px" style="margin: 0pt; padding: 0pt; background-color: rgb(255, 255, 255); border-collapse: collapse;">';
            $text = $text.'<tr height="138px">';
            $text = $text.'<td><img src="http://' .$_SERVER['HTTP_HOST']. '/images/email/logo_empty.jpg" alt="Showcode." title="Showcode." style="margin: 0pt; padding: 0pt; border: 0pt none; display: block;"></td>';
            $text = $text.'</tr>';
            $text = $text.'<tr height="41px">';
            $text = $text.'<td><p style="font-family:Arial, Helvetica, sans-serif; font-size:24px; font-weight:normal; font-style:normal; color:#333;">Здравствуйте, '.Yii::app()->user->name.'.</p></td>';
            $text = $text.'</tr>';
            $text = $text.'<tr>';
            $text = $text.'<td style="background-color:#e5e5e5;"><p style="padding:16px 10px; font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#333;">Вы запросили список всех билетов для мероприятия под названием «<a target="_blank" title="' .$this->title. '" href="http://' .$_SERVER['HTTP_HOST']. '/events/view/' .$this->id. '">' .$this->title. '</a>»</p></td>';
            $text = $text.'</tr>';
            $text = $text.'<tr>';
            $text = $text.'<td style="padding-top:15px; padding-bottom:15px; border-bottom-width:1px; border-bottom-color:#999999; border-bottom-style:solid;">';
            $text = $text.'<table cellspasing="0" border="0" cellpadding="5" width="" style="text-align: center; margin: 0pt; padding: 20px 0; background-color: rgb(255, 255, 255); border-collapse: collapse;">';
            $text = $text.'<tr style="border: 1px solid #000;">';
                $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$tickets[0]->getAttributeLabel('user_id'). '</td>';
                $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$tickets[0]->getAttributeLabel('status'). '</td>';
                $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$tickets[0]->getAttributeLabel('quantity'). '</td>';
                $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$tickets[0]->getAttributeLabel('total'). '</td>';
                $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$tickets[0]->getAttributeLabel('column'). '</td>';
                $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$tickets[0]->getAttributeLabel('place'). '</td>';
                $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$tickets[0]->getAttributeLabel('uniq'). '</td>';
                $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$tickets[0]->getAttributeLabel('payment'). '</td>';
            $text = $text.'</tr>';
            foreach($tickets AS $i => $ticket){
                $text = $text.'<tr style="border-bottom: 1px solid #000;">';
                    if($ticket->user_id)
                        $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' . Yii::app()->user->getAuthorName($ticket->user_id). '</td>';
                    else
                        $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$ticket->family. '</td>';
                    $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .TransactionLog::$status[$ticket->status]. '</td>';
                    $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$ticket->quantity. '</td>';
                    $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$ticket->total. '</td>';
                    $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$ticket->column. '</td>';
                    $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$ticket->place. '</td>';
                    $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$ticket->uniq. '</td>';
                    $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .TransactionLog::$payment_type[$ticket->payment]. '</td>';
                $text = $text.'</tr>';
            }
            $text = $text.'</table>';
            $text = $text.'</td>';
            $text = $text.'</tr>';
            $text = $text.'<tr>';
            $text = $text.'<td style="padding-top:5px;"><p style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#333;">С уважением, администрация сайта <a target="_blank" href="' .$_SERVER['HTTP_HOST']. '" title="">ShowCode.ru</a>.</p></td>';
            $text = $text.'</tr>';
            $text = $text.'</table>';

            return $text;
	}
}