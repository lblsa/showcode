<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property integer $user_id
 * @property string $email
 * @property string $password
 * @property string $name
 * @property string $role
 * @property string $organization
 * @property string $profile
 * @property mixed $ROLE Массив всевозможный ролей пользователей.
 * @property string $oldPassword Переменная, для хранения бывшего пароля пользователя. Для проверки при смене пароля.
 * @property string $newPassword Переменная, для хранения нового пароля пользователя при смене пароля.
 */

class User extends CActiveRecord
{
	public static $ROLE = array(
		'user'=>'Пользователь',
		'organizer'=>'Организатор',
		'admin'=>'Администратор'
		);
	public $oldPassword;
	public $newPassword;
    public $repeatPassword;

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
		/*if ($this->email && User::model()->exists('email = "' .$this->email. '" and user_id != "' .Yii::app()->user->id. '" and phone = "' .$this->phone. '"'))
			$this->addError('email','Такой e-mail адрес уже существует');*/
		if (User::model()->exists("`phone` = :phone and `user_id` != :userid", array(':phone'=>$this->phone, ':userid'=>Yii::app()->user->id)))
			$this->addError('phone','Пользователь с таким номером телефона уже существует');
		if(!$this->isNewRecord)
        {
            $oldPasswordDB = User::model()->findByPk(Yii::app()->user->id, array('select'=>'password'))->password;

            $curOldPass = $this->oldPassword;
            $curOldPassMd5 = md5($this->oldPassword);
            $oldPass = $curOldPass.'/'.$curOldPassMd5;

            if ($this->newPassword == '' && $this->repeatPassword == '')
                $this->password=$oldPasswordDB; 
            else if ($oldPass == $oldPasswordDB)
            {
                if($this->newPassword == $this->repeatPassword)
                    $this->password = $this->newPassword.'/'.md5($this->newPassword);
                else
                    $this->addError('password','Новый пароль не совпадает');
            }
            else
                $this->addError('password','Старый пароль не совпадает');
        }
		return parent::beforeValidate();
    }

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
				$this->uniq = md5($this->name.time());
				if($this->type == 'self'){
					$yourPassword = $this->generatePassword(10);
					$this->password = $yourPassword.'/'.md5($yourPassword);
				}

				if(User::model()->exists('phone = "7' .$this->phone. '"')){
					$this->addError('phone','Пользователь с таким номером телефона уже зарегистрирован в системе');
				}
			}

            if (!$this->email)
				$this->email=NULL;

			//Проверка, что админа может создать только админ
			if ($this->role=='admin' and !Yii::app()->user->isAdmin())
				return false;

			if ($this->phone && count($this->errors)==0){
				$this->phone = '7' .$this->phone;
				if($this->type == 'self' && $this->isNewRecord){
					if($this->email){
						$text = Yii::app()->controller->->getTextEmailAboutRegistration($yourPassword, $this);
						Yii::app()->mf->mail_html($this->email,'noreply@'.$_SERVER[HTTP_HOST],Yii::app()->name,$text,'Регистрация в ' .Yii::app()->name. '!');
					}
					$message = Yii::app()->name. '.Пароль:' .$yourPassword;
					$this->sendMessenge($message);
				}
			}else{
				return false;
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
	 * На данном этапе пользователю отправляется пароль.
	 */
	public function sendMessenge($message, $phone=NULL, $flag = 1)
	{
		if (isset($message)){
			//Отправляем пользователю смс.
			if(isset($phone))
				$p = $phone;
			else
				$p = $this->phone;
			if (isset($p)){
				require_once('./soap/sms24x7.php');
				$EMAIL_SMS = 'rubtsov@complexsys.ru';
				$PASSWORD_SMS = 'MoZBdJsXG8';

				$r = smsapi_push_msg_nologin($EMAIL_SMS, $PASSWORD_SMS, $p, $message, array("unicode"=>"1"));
				
				if($flag==1)
				{ 
					//отправляем уведомление, что появился новый пользователь
					$message = 'В системе зарегистрировался новый пользователь. Телефон: '.$this->phone;
					Yii::app()->mf->mail_html('showcode@googlegroups.com','noreply@'.$_SERVER[HTTP_HOST],Yii::app()->name,$message,'В системе ' .Yii::app()->name. ' новый пользователь!');
					Yii::app()->mf->mail_html('vladimir.stasevich@gmail.com','noreply@'.$_SERVER[HTTP_HOST],Yii::app()->name,$message,'В системе ' .Yii::app()->name. ' новый пользователь!');
					Yii::app()->mf->mail_html('isezonov@gmail.com','noreply@'.$_SERVER[HTTP_HOST],Yii::app()->name,$message,'В системе ' .Yii::app()->name. ' новый пользователь!');
					Yii::app()->mf->mail_html('roman.efimushkin@gmail.com','noreply@'.$_SERVER[HTTP_HOST],Yii::app()->name,$message,'В системе ' .Yii::app()->name. ' новый пользователь!');
				}
			}
		}
	}

	/**
	 * Возвращает верстку текста для электронной почты при регистрации.
	 */

	/**
	 * Возвращает верстку текста для электронной почты при востановленни пароля.
	 */
	/**
	 * Checks if the given password is correct.
	 * @param string the password to be validated
	 * @return boolean whether the password is valid
	 */
	public function validatePassword($password)
    {
        if (($password)===$this->password)
            return true;

        return false;
    }

	/**
	 * Generates the password hash.
	 * @param string $password.
	 * @return string hash.
	 */
    public function hashPassword($password)
    {
        return md5($password);
    }

    /**
	 * Generates the password.
	 * @param integer $number.
	 * @return string password.
	 */
    public function generatePassword($number)
    {
        //$arr = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','r','s','t','u','v','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','R','S','T','U','V','X','Y','Z','1','2','3','4','5','6','7','8','9','0',
        $arrNumber = array('1','2','3','4','5','6','7','8','9','0');
        $arr = array("a",     "abe",   "ace",   "act",   "ad",    "ada",   "add",
        "ago",   "aid",   "aim",   "air",   "all",   "alp",   "am",    "amy",
        "an",    "ana",   "and",   "ann",   "ant",   "any",   "ape",   "aps",
        "apt",   "arc",   "are",   "ark",   "arm",   "art",   "as",    "ash",
        "ask",   "at",    "ate",   "aug",   "auk",   "ave",   "awe",   "awk",
        "awl",   "awn",   "ax",    "aye",   "bad",   "bag",   "bah",   "bam",
        "ban",   "bar",   "bat",   "bay",   "be",    "bed",   "bee",   "beg",
        "ben",   "bet",   "bey",   "bib",   "bid",   "big",   "bin",   "bit",
        "bob",   "bog",   "bon",   "boo",   "bop",   "bow",   "boy",   "bub",
        "bud",   "bug",   "bum",   "bun",   "bus",   "but",   "buy",   "by",
        "bye",   "cab",   "cal",   "cam",   "can",   "cap",   "car",   "cat",
        "caw",   "cod",   "cog",   "col",   "con",   "coo",   "cop",   "cot",
        "cow",   "coy",   "cry",   "cub",   "cue",   "cup",   "cur",   "cut",
        "dab",   "dad",   "dam",   "dan",   "dar",   "day",   "dee",   "del",
        "den",   "des",   "dew",   "did",   "die",   "dig",   "din",   "dip",
        "do",    "doe",   "dog",   "don",   "dot",   "dow",   "dry",   "dub",
        "dud",   "due",   "dug",   "dun",   "ear",   "eat",   "ed",    "eel",
        "egg",   "ego",   "eli",   "elk",   "elm",   "ely",   "em",    "end",
        "est",   "etc",   "eva",   "eve",   "ewe",   "eye",   "fad",   "fan",
        "far",   "fat",   "fay",   "fed",   "fee",   "few",   "fib",   "fig",
        "fin",   "fir",   "fit",   "flo",   "fly",   "foe",   "fog",   "for",
        "fry",   "fum",   "fun",   "fur",   "gab",   "gad",   "gag",   "gal",
        "gam",   "gap",   "gas",   "gay",   "gee",   "gel",   "gem",   "get",
        "gig",   "gil",   "gin",   "go",    "got",   "gum",   "gun",   "gus",
        "gut",   "guy",   "gym",   "gyp",   "ha",    "had",   "hal",   "ham",
        "han",   "hap",   "has",   "hat",   "haw",   "hay",   "he",    "hem",
        "hen",   "her",   "hew",   "hey",   "hi",    "hid",   "him",   "hip",
        "his",   "hit",   "ho",    "hob",   "hoc",   "hoe",   "hog",   "hop",
        "hot",   "how",   "hub",   "hue",   "hug",   "huh",   "hum",   "hut",
        "i",     "icy",   "ida",   "if",    "ike",   "ill",   "ink",   "inn",
        "io",    "ion",   "iq",    "ira",   "ire",   "irk",   "is",    "it",
        "its",   "ivy",   "jab",   "jag",   "jam",   "jan",   "jar",   "jaw",
        "jay",   "jet",   "jig",   "jim",   "jo",    "job",   "joe",   "jog",
        "jot",   "joy",   "jug",   "jut",   "kay",   "keg",   "ken",   "key",
        "kid",   "kim",   "kin",   "kit",   "la",    "lab",   "lac",   "lad",
        "lag",   "lam",   "lap",   "law",   "lay",   "lea",   "led",   "lee",
        "leg",   "len",   "leo",   "let",   "lew",   "lid",   "lie",   "lin",
        "lip",   "lit",   "lo",    "lob",   "log",   "lop",   "los",   "lot",
        "lou",   "low",   "loy",   "lug",   "lye",   "ma",    "mac",   "mad",
        "mae",   "man",   "mao",   "map",   "mat",   "maw",   "may",   "me",
        "meg",   "mel",   "men",   "met",   "mew",   "mid",   "min",   "mit",
        "mob",   "mod",   "moe",   "moo",   "mop",   "mos",   "mot",   "mow",
        "mud",   "mug",   "mum",   "my",    "nab",   "nag",   "nan",   "nap",
        "nat",   "nay",   "ne",    "ned",   "nee",   "net",   "new",   "nib",
        "nil",   "nip",   "nit",   "no",    "nob",   "nod",   "non",   "nor",
        "not",   "nov",   "now",   "nu",    "nun",   "nut",   "o",     "oaf",
        "oak",   "oar",   "oat",   "odd",   "ode",   "of",    "off",   "oft",
        "oh",    "oil",   "ok",    "old",   "on",    "one",   "or",    "orb",
        "ore",   "orr",   "os",    "ott",   "our",   "out",   "ova",   "ow",
        "owe",   "owl",   "own",   "ox",    "pa",    "pad",   "pal",   "pam",
        "pan",   "pap",   "par",   "pat",   "paw",   "pay",   "pea",   "peg",
        "pen",   "pep",   "per",   "pet",   "pew",   "phi",   "pi",    "pie",
        "pin",   "pit",   "ply",   "po",    "pod",   "poe",   "pop",   "pot",
        "pow",   "pro",   "pry",   "pub",   "pug",   "pun",   "pup",   "put",
        "quo",   "rag",   "ram",   "ran",   "rap",   "rat",   "raw",   "ray",
        "reb",   "red",   "rep",   "ret",   "rib",   "rid",   "rig",   "rim",
        "rio",   "rip",   "rob",   "rod",   "roe",   "ron",   "rot",   "row",
        "roy",   "rub",   "rue",   "rug",   "rum",   "run",   "rye",   "sac",
        "sad",   "sag",   "sal",   "sam",   "san",   "sap",   "sat",   "saw",
        "say",   "sea",   "sec",   "see",   "sen",   "set",   "sew",   "she",
        "shy",   "sin",   "sip",   "sir",   "sis",   "sit",   "ski",   "sky",
        "sly",   "so",    "sob",   "sod",   "son",   "sop",   "sow",   "soy",
        "spa",   "spy",   "sub",   "sud",   "sue",   "sum",   "sun",   "sup",
        "tab",   "tad",   "tag",   "tan",   "tap",   "tar",   "tea",   "ted",
        "tee",   "ten",   "the",   "thy",   "tic",   "tie",   "tim",   "tin",
        "tip",   "to",    "toe",   "tog",   "tom",   "ton",   "too",   "top",
        "tow",   "toy",   "try",   "tub",   "tug",   "tum",   "tun",   "two",
        "un",    "up",    "us",    "use",   "van",   "vat",   "vet",   "vie",
        "wad",   "wag",   "war",   "was",   "way",   "we",    "web",   "wed",
        "wee",   "wet",   "who",   "why",   "win",   "wit",   "wok",   "won",
        "woo",   "wow",   "wry",   "wu",    "yam",   "yap",   "yaw",   "ye",
        "yea",   "yes",   "yet",   "you",   "abed",  "abel",  "abet",  "able",
        "abut",  "ache",  "acid",  "acme",  "acre",  "acta",  "acts",  "adam",
        "adds",  "aden",  "afar",  "afro",  "agee",  "ahem",  "ahoy",  "aida",
        "aide",  "aids",  "airy",  "ajar",  "akin",  "alan",  "alec",  "alga",
        "alia",  "ally",  "alma",  "aloe",  "also",  "alto",  "alum",  "alva",
        "amen",  "ames",  "amid",  "ammo",  "amok",  "amos",  "amra",  "andy",
        "anew",  "anna",  "anne",  "ante",  "anti",  "aqua",  "arab",  "arch",
        "area",  "argo",  "arid",  "army",  "arts",  "arty",  "asia",  "asks",
        "atom",  "aunt",  "aura",  "auto",  "aver",  "avid",  "avis",  "avon",
        "avow",  "away",  "awry",  "babe",  "baby",  "bach",  "back",  "bade",
        "bail",  "bait",  "bake",  "bald",  "bale",  "bali",  "balk",  "ball",
        "balm",  "band",  "bane",  "bang",  "bank",  "barb",  "bard",  "bare",
        "bark",  "barn",  "barr",  "base",  "bash",  "bask",  "bass",  "bate",
        "bath",  "bawd",  "bawl",  "bead",  "beak",  "beam",  "bean",  "bear",
        "beat",  "beau",  "beck",  "beef",  "been",  "beer",  "beet",  "bela",
        "bell",  "belt",  "bend",  "bent",  "berg",  "bern",  "bert",  "bess",
        "best",  "beta",  "beth",  "bhoy",  "bias",  "bide",  "bien",  "bile",
        "bilk",  "bill",  "bind",  "bing",  "bird",  "bite",  "bits",  "blab",
        "blat",  "bled",  "blew",  "blob",  "bloc",  "blot",  "blow",  "blue",
        "blum",  "blur",  "boar",  "boat",  "boca",  "bock",  "bode",  "body",
        "bogy",  "bohr",  "boil",  "bold",  "bolo",  "bolt",  "bomb",  "bona",
        "bond",  "bone",  "bong",  "bonn",  "bony",  "book",  "boom",  "boon",
        "boot",  "bore",  "borg",  "born",  "bose",  "boss",  "both",  "bout",
        "bowl",  "boyd",  "brad",  "brae",  "brag",  "bran",  "bray",  "bred",
        "brew",  "brig",  "brim",  "brow",  "buck",  "budd",  "buff",  "bulb",
        "bulk",  "bull",  "bunk",  "bunt",  "buoy",  "burg",  "burl",  "burn",
        "burr",  "burt",  "bury",  "bush",  "buss",  "bust",  "busy",  "byte",
        "cady",  "cafe",  "cage",  "cain",  "cake",  "calf",  "call",  "calm",
        "came",  "cane",  "cant",  "card",  "care",  "carl",  "carr",  "cart",
        "case",  "cash",  "cask",  "cast",  "cave",  "ceil",  "cell",  "cent",
        "cern",  "chad",  "char",  "chat",  "chaw",  "chef",  "chen",  "chew",
        "chic",  "chin",  "chou",  "chow",  "chub",  "chug",  "chum",  "cite",
        "city",  "clad",  "clam",  "clan",  "claw",  "clay",  "clod",  "clog",
        "clot",  "club",  "clue",  "coal",  "coat",  "coca",  "cock",  "coco",
        "coda",  "code",  "cody",  "coed",  "coil",  "coin",  "coke",  "cola",
        "cold",  "colt",  "coma",  "comb",  "come",  "cook",  "cool",  "coon",
        "coot",  "cord",  "core",  "cork",  "corn",  "cost",  "cove",  "cowl",
        "crab",  "crag",  "cram",  "cray",  "crew",  "crib",  "crow",  "crud",
        "cuba",  "cube",  "cuff",  "cull",  "cult",  "cuny",  "curb",  "curd",
        "cure",  "curl",  "curt",  "cuts",  "dade",  "dale",  "dame",  "dana",
        "dane",  "dang",  "dank",  "dare",  "dark",  "darn",  "dart",  "dash",
        "data",  "date",  "dave",  "davy",  "dawn",  "days",  "dead",  "deaf",
        "deal",  "dean",  "dear",  "debt",  "deck",  "deed",  "deem",  "deer",
        "deft",  "defy",  "dell",  "dent",  "deny",  "desk",  "dial",  "dice",
        "died",  "diet",  "dime",  "dine",  "ding",  "dint",  "dire",  "dirt",
        "disc",  "dish",  "disk",  "dive",  "dock",  "does",  "dole",  "doll",
        "dolt",  "dome",  "done",  "doom",  "door",  "dora",  "dose",  "dote",
        "doug",  "dour",  "dove",  "down",  "drab",  "drag",  "dram",  "draw",
        "drew",  "drub",  "drug",  "drum",  "dual",  "duck",  "duct",  "duel",
        "duet",  "duke",  "dull",  "dumb",  "dune",  "dunk",  "dusk",  "dust",
        "duty",  "each",  "earl",  "earn",  "ease",  "east",  "easy",  "eben",
        "echo",  "eddy",  "eden",  "edge",  "edgy",  "edit",  "edna",  "egan",
        "elan",  "elba",  "ella",  "else",  "emil",  "emit",  "emma",  "ends",
        "eric",  "eros",  "even",  "ever",  "evil",  "eyed",  "face",  "fact",
        "fade",  "fail",  "fain",  "fair",  "fake",  "fall",  "fame",  "fang",
        "farm",  "fast",  "fate",  "fawn",  "fear",  "feat",  "feed",  "feel",
        "feet",  "fell",  "felt",  "fend",  "fern",  "fest",  "feud",  "fief",
        "figs",  "file",  "fill",  "film",  "find",  "fine",  "fink",  "fire",
        "firm",  "fish",  "fisk",  "fist",  "fits",  "five",  "flag",  "flak",
        "flam",  "flat",  "flaw",  "flea",  "fled",  "flew",  "flit",  "floc",
        "flog",  "flow",  "flub",  "flue",  "foal",  "foam",  "fogy",  "foil",
        "fold",  "folk",  "fond",  "font",  "food",  "fool",  "foot",  "ford",
        "fore",  "fork",  "form",  "fort",  "foss",  "foul",  "four",  "fowl",
        "frau",  "fray",  "fred",  "free",  "fret",  "frey",  "frog",  "from",
        "fuel",  "full",  "fume",  "fund",  "funk",  "fury",  "fuse",  "fuss",
        "gaff",  "gage",  "gail",  "gain",  "gait",  "gala",  "gale",  "gall",
        "galt",  "game",  "gang",  "garb",  "gary",  "gash",  "gate",  "gaul",
        "gaur",  "gave",  "gawk",  "gear",  "geld",  "gene",  "gent",  "germ",
        "gets",  "gibe",  "gift",  "gild",  "gill",  "gilt",  "gina",  "gird",
        "girl",  "gist",  "give",  "glad",  "glee",  "glen",  "glib",  "glob",
        "glom",  "glow",  "glue",  "glum",  "glut",  "goad",  "goal",  "goat",
        "goer",  "goes",  "gold",  "golf",  "gone",  "gong",  "good",  "goof",
        "gore",  "gory",  "gosh",  "gout",  "gown",  "grab",  "grad",  "gray",
        "greg",  "grew",  "grey",  "grid",  "grim",  "grin",  "grit",  "grow",
        "grub",  "gulf",  "gull",  "gunk",  "guru",  "gush",  "gust",  "gwen",
        "gwyn",  "haag",  "haas",  "hack",  "hail",  "hair",  "hale",  "half",
        "hall",  "halo",  "halt",  "hand",  "hang",  "hank",  "hans",  "hard",
        "hark",  "harm",  "hart",  "hash",  "hast",  "hate",  "hath",  "haul",
        "have",  "hawk",  "hays",  "head",  "heal",  "hear",  "heat",  "hebe",
        "heck",  "heed",  "heel",  "heft",  "held",  "hell",  "helm",  "herb",
        "herd",  "here",  "hero",  "hers",  "hess",  "hewn",  "hick",  "hide",
        "high",  "hike",  "hill",  "hilt",  "hind",  "hint",  "hire",  "hiss",
        "hive",  "hobo",  "hock",  "hoff",  "hold",  "hole",  "holm",  "holt",
        "home",  "hone",  "honk",  "hood",  "hoof",  "hook",  "hoot",  "horn",
        "hose",  "host",  "hour",  "hove",  "howe",  "howl",  "hoyt",  "huck",
        "hued",  "huff",  "huge",  "hugh",  "hugo",  "hulk",  "hull",  "hunk",
        "hunt",  "hurd",  "hurl",  "hurt",  "hush",  "hyde",  "hymn",  "ibis",
        "icon",  "idea",  "idle",  "iffy",  "inca",  "inch",  "into",  "ions",
        "iota",  "iowa",  "iris",  "irma",  "iron",  "isle",  "itch",  "item",
        "ivan",  "jack",  "jade",  "jail",  "jake",  "jane",  "java",  "jean",
        "jeff",  "jerk",  "jess",  "jest",  "jibe",  "jill",  "jilt",  "jive",
        "joan",  "jobs",  "jock",  "joel",  "joey",  "john",  "join",  "joke",
        "jolt",  "jove",  "judd",  "jude",  "judo",  "judy",  "juju",  "juke",
        "july",  "june",  "junk",  "juno",  "jury",  "just",  "jute",  "kahn",
        "kale",  "kane",  "kant",  "karl",  "kate",  "keel",  "keen",  "keno",
        "kent",  "kern",  "kerr",  "keys",  "kick",  "kill",  "kind",  "king",
        "kirk",  "kiss",  "kite",  "klan",  "knee",  "knew",  "knit",  "knob",
        "knot",  "know",  "koch",  "kong",  "kudo",  "kurd",  "kurt",  "kyle",
        "lace",  "lack",  "lacy",  "lady",  "laid",  "lain",  "lair",  "lake",
        "lamb",  "lame",  "land",  "lane",  "lang",  "lard",  "lark",  "lass",
        "last",  "late",  "laud",  "lava",  "lawn",  "laws",  "lays",  "lead",
        "leaf",  "leak",  "lean",  "lear",  "leek",  "leer",  "left",  "lend",
        "lens",  "lent",  "leon",  "lesk",  "less",  "lest",  "lets",  "liar",
        "lice",  "lick",  "lied",  "lien",  "lies",  "lieu",  "life",  "lift",
        "like",  "lila",  "lilt",  "lily",  "lima",  "limb",  "lime",  "lind",
        "line",  "link",  "lint",  "lion",  "lisa",  "list",  "live",  "load",
        "loaf",  "loam",  "loan",  "lock",  "loft",  "loge",  "lois",  "lola",
        "lone",  "long",  "look",  "loon",  "loot",  "lord",  "lore",  "lose",
        "loss",  "lost",  "loud",  "love",  "lowe",  "luck",  "lucy",  "luge",
        "luke",  "lulu",  "lund",  "lung",  "lura",  "lure",  "lurk",  "lush",
        "lust",  "lyle",  "lynn",  "lyon",  "lyra",  "mace",  "made",  "magi",
        "maid",  "mail",  "main",  "make",  "male",  "mali",  "mall",  "malt",
        "mana",  "mann",  "many",  "marc",  "mare",  "mark",  "mars",  "mart",
        "mary",  "mash",  "mask",  "mass",  "mast",  "mate",  "math",  "maul",
        "mayo",  "mead",  "meal",  "mean",  "meat",  "meek",  "meet",  "meld",
        "melt",  "memo",  "mend",  "menu",  "mert",  "mesh",  "mess",  "mice",
        "mike",  "mild",  "mile",  "milk",  "mill",  "milt",  "mimi",  "mind",
        "mine",  "mini",  "mink",  "mint",  "mire",  "miss",  "mist",  "mite",
        "mitt",  "moan",  "moat",  "mock",  "mode",  "mold",  "mole",  "moll",
        "molt",  "mona",  "monk",  "mont",  "mood",  "moon",  "moor",  "moot",
        "more",  "morn",  "mort",  "moss",  "most",  "moth",  "move",  "much",
        "muck",  "mudd",  "muff",  "mule",  "mull",  "murk",  "mush",  "must",
        "mute",  "mutt",  "myra",  "myth",  "nagy",  "nail",  "nair",  "name",
        "nary",  "nash",  "nave",  "navy",  "neal",  "near",  "neat",  "neck",
        "need",  "neil",  "nell",  "neon",  "nero",  "ness",  "nest",  "news",
        "newt",  "nibs",  "nice",  "nick",  "nile",  "nina",  "nine",  "noah",
        "node",  "noel",  "noll",  "none",  "nook",  "noon",  "norm",  "nose",
        "note",  "noun",  "nova",  "nude",  "null",  "numb",  "oath",  "obey",
        "oboe",  "odin",  "ohio",  "oily",  "oint",  "okay",  "olaf",  "oldy",
        "olga",  "olin",  "oman",  "omen",  "omit",  "once",  "ones",  "only",
        "onto",  "onus",  "oral",  "orgy",  "oslo",  "otis",  "otto",  "ouch",
        "oust",  "outs",  "oval",  "oven",  "over",  "owly",  "owns",  "quad",
        "quit",  "quod",  "race",  "rack",  "racy",  "raft",  "rage",  "raid",
        "rail",  "rain",  "rake",  "rank",  "rant",  "rare",  "rash",  "rate",
        "rave",  "rays",  "read",  "real",  "ream",  "rear",  "reck",  "reed",
        "reef",  "reek",  "reel",  "reid",  "rein",  "rena",  "rend",  "rent",
        "rest",  "rice",  "rich",  "rick",  "ride",  "rift",  "rill",  "rime",
        "ring",  "rink",  "rise",  "risk",  "rite",  "road",  "roam",  "roar",
        "robe",  "rock",  "rode",  "roil",  "roll",  "rome",  "rood",  "roof",
        "rook",  "room",  "root",  "rosa",  "rose",  "ross",  "rosy",  "roth",
        "rout",  "rove",  "rowe",  "rows",  "rube",  "ruby",  "rude",  "rudy",
        "ruin",  "rule",  "rung",  "runs",  "runt",  "ruse",  "rush",  "rusk",
        "russ",  "rust",  "ruth",  "sack",  "safe",  "sage",  "said",  "sail",
        "sale",  "salk",  "salt",  "same",  "sand",  "sane",  "sang",  "sank",
        "sara",  "saul",  "save",  "says",  "scan",  "scar",  "scat",  "scot",
        "seal",  "seam",  "sear",  "seat",  "seed",  "seek",  "seem",  "seen",
        "sees",  "self",  "sell",  "send",  "sent",  "sets",  "sewn",  "shag",
        "sham",  "shaw",  "shay",  "shed",  "shim",  "shin",  "shod",  "shoe",
        "shot",  "show",  "shun",  "shut",  "sick",  "side",  "sift",  "sigh",
        "sign",  "silk",  "sill",  "silo",  "silt",  "sine",  "sing",  "sink",
        "sire",  "site",  "sits",  "situ",  "skat",  "skew",  "skid",  "skim",
        "skin",  "skit",  "slab",  "slam",  "slat",  "slay",  "sled",  "slew",
        "slid",  "slim",  "slit",  "slob",  "slog",  "slot",  "slow",  "slug",
        "slum",  "slur",  "smog",  "smug",  "snag",  "snob",  "snow",  "snub",
        "snug",  "soak",  "soar",  "sock",  "soda",  "sofa",  "soft",  "soil",
        "sold",  "some",  "song",  "soon",  "soot",  "sore",  "sort",  "soul",
        "sour",  "sown",  "stab",  "stag",  "stan",  "star",  "stay",  "stem",
        "stew",  "stir",  "stow",  "stub",  "stun",  "such",  "suds",  "suit",
        "sulk",  "sums",  "sung",  "sunk",  "sure",  "surf",  "swab",  "swag",
        "swam",  "swan",  "swat",  "sway",  "swim",  "swum",  "tack",  "tact",
        "tail",  "take",  "tale",  "talk",  "tall",  "tank",  "task",  "tate",
        "taut",  "teal",  "team",  "tear",  "tech",  "teem",  "teen",  "teet",
        "tell",  "tend",  "tent",  "term",  "tern",  "tess",  "test",  "than",
        "that",  "thee",  "them",  "then",  "they",  "thin",  "this",  "thud",
        "thug",  "tick",  "tide",  "tidy",  "tied",  "tier",  "tile",  "till",
        "tilt",  "time",  "tina",  "tine",  "tint",  "tiny",  "tire",  "toad",
        "togo",  "toil",  "told",  "toll",  "tone",  "tong",  "tony",  "took",
        "tool",  "toot",  "tore",  "torn",  "tote",  "tour",  "tout",  "town",
        "trag",  "tram",  "tray",  "tree",  "trek",  "trig",  "trim",  "trio",
        "trod",  "trot",  "troy",  "true",  "tuba",  "tube",  "tuck",  "tuft",
        "tuna",  "tune",  "tung",  "turf",  "turn",  "tusk",  "twig",  "twin",
        "twit",  "ulan",  "unit",  "urge",  "used",  "user",  "uses",  "utah",
        "vail",  "vain",  "vale",  "vary",  "vase",  "vast",  "veal",  "veda",
        "veil",  "vein",  "vend",  "vent",  "verb",  "very",  "veto",  "vice",
        "view",  "vine",  "vise",  "void",  "volt",  "vote",  "wack",  "wade",
        "wage",  "wail",  "wait",  "wake",  "wale",  "walk",  "wall",  "walt",
        "wand",  "wane",  "wang",  "want",  "ward",  "warm",  "warn",  "wart",
        "wash",  "wast",  "wats",  "watt",  "wave",  "wavy",  "ways",  "weak",
        "weal",  "wean",  "wear",  "weed",  "week",  "weir",  "weld",  "well",
        "welt",  "went",  "were",  "wert",  "west",  "wham",  "what",  "whee",
        "when",  "whet",  "whoa",  "whom",  "wick",  "wife",  "wild",  "will",
        "wind",  "wine",  "wing",  "wink",  "wino",  "wire",  "wise",  "wish",
        "with",  "wolf",  "wont",  "wood",  "wool",  "word",  "wore",  "work",
        "worm",  "worn",  "wove",  "writ",  "wynn",  "yale",  "yang",  "yank",
        "yard",  "yarn",  "yawl",  "yawn",  "yeah",  "year",  "yell",  "yoga",
        "yoke"   );
		// Генерируем пароль
		$pass = "";
		$param=count($arr) - 1;
                $i = 0;
		while($i < $number)
		{
			// Вычисляем случайный индекс массива
                        if($i > 0){
                            $pass .= $arrNumber[rand(0,  count($arrNumber) - 1)];
                        }
			$index = rand(0, $param);
			$pass .= $arr[$index];
			$i = strlen($pass);
		}
		return $pass;
    }

	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
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
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('phone, name, role, email', 'required', 'message'=>'Не может быть пустым'),
			array('vkontakte_id', 'unique', 'message'=>'Пользователь с вашим id уже зарегистрирован в системе'),
            array('email', 'unique'),
            array('phone', 'unique'),
			array('uniq, email, password, name, oldPassword, newPassword, repeatPassword, organization, phone', 'length', 'max'=>128),
			array('phone', 'match', 'pattern'=>'/^[\d]{10}$/', 'message'=>'Телефонный номер должен состоять из 10 цифр'),
			array('role', 'length', 'max'=>10),
			array('email','email'),
			array('send_mail', 'boolean'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, email, password, name, role, organization, profile, phone, send_mail', 'safe', 'on'=>'search'),
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
                    'events'=>array(self::HAS_MANY, 'Events', 'author')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'id',
			'uniq' => 'Уникальный пароль для Web API',
			'email' => 'Email',
			'password' => 'Пароль',
            'newPassword' => 'Новый пароль',
            'repeatPassword' => 'Повторить пароль',
			'name' => 'Фамилия Имя Отчество',
			'role' => 'Роль',
			'organization' => 'Название организации',
			'profile' => 'Профиль',
			'phone' => 'Мобильный телефон (10 цифр)',
			'send_mail' => 'Присылать отзывы',
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

		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('role',$this->role,true);
		$criteria->compare('organization',$this->organization,true);
		$criteria->compare('profile',$this->profile,true);
		$criteria->compare('send_mail',$this->send_mail,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
