unit Emul;

interface

uses
  Windows, Messages, SysUtils, Variants, Classes, Graphics, Controls, Forms,
  Dialogs, ExtCtrls, StdCtrls, Grids, Buttons, XStringGrid, CECheckbox,
  Spin, Menus,ClipBrd, FolderBrowser;

type
  TForm1 = class(TForm)
    Panel1: TPanel;
    Panel2: TPanel;
    Panel3: TPanel;
    OpsGrid: TStringGrid;
    GroupBox1: TGroupBox;
    Splitter1: TSplitter;
    Panel4: TPanel;
    Label1: TLabel;
    Label2: TLabel;
    PostIp: TEdit;
    PostPort: TEdit;
    BtnExec: TBitBtn;
    TransGrid: TXStringGrid;
    EditCellEditor1: TEditCellEditor;
    Panel5: TPanel;
    GroupBox2: TGroupBox;
    ResultLabel: TLabel;
    TimeRun: TSpinEdit;
    Label3: TLabel;
    PopMenu: TPopupMenu;
    N1: TMenuItem;
    Timer1: TTimer;
    FB: TFolderBrowser;
    Timer2: TTimer;
    UseEvents: TCheckBox;
    Timer3: TTimer;
    Timer4: TTimer;
    procedure FormCreate(Sender: TObject);
    procedure EditCellEditor1ElipsisClick(Sender: TObject);
    procedure OpsGridSelectCell(Sender: TObject; ACol, ARow: Integer; var CanSelect: Boolean);
    procedure FormDestroy(Sender: TObject);
    procedure BtnExecClick(Sender: TObject);
    procedure TransGridSelectCell(Sender: TObject; ACol, ARow: Integer;var CanSelect: Boolean);
    procedure TransGridMouseDown(Sender: TObject; Button: TMouseButton;
      Shift: TShiftState; X, Y: Integer);
    procedure N1Click(Sender: TObject);
    procedure Timer1Timer(Sender: TObject);
    procedure Timer2Timer(Sender: TObject);
    procedure Timer3Timer(Sender: TObject);
    procedure Timer4Timer(Sender: TObject);
  private
    { Private declarations }
     FieldsStr:array[0..99] of String;              // массив значений полей для транзакции
     ResponseFields:array[0..99] of String;         // массив значений полей в ответе
     function ExecTransaction(timeout:Integer; useEvent:Boolean):Boolean;
     function GetValueFields:Boolean;              // получает введенные в TransGrid значения для полей из колонки 'Запрос' в FieldsStr
     procedure SetValueFields;                    // устанавливает значения полей в колонке 'Ответ' в TransGrid из ResponseFields

     function GetField(id:Integer):String;          // получает значение поля по номеру из  FieldsStr
     procedure SetField(id:Integer;value:String);   // устанавливает значение поля по номеру в ResponseFields
  public
    { Public declarations }
    procedure ContactLess_EventHandle(Sender: TObject;Result:Integer);
    procedure ContactLess_PinEventHandle(Sender: TObject;const PAN: WideString; const EWK: WideString; MkIndex: Integer);
    function CheckExec:Boolean;
  end;

var
  Form1: TForm1;
  str:array[0..99] of TStringList;


implementation

{$R *.dfm}

uses ComObj,LanCom_TLB,POSEntrySel, RunForm, CurrencySel;

var  TransResult,BufferString:String;
     Exec: TransSender=nil;
     WrapperExec:TTransSender=nil;
     useSelForm:Boolean=false;
     useSelDir:Boolean=false;
     useSelCurrency:Boolean=false;
     LastDir:String='X';
     LastEntryMode:Integer=3;
     startloop:Boolean;
     RetryCount:Integer=0;   
// ---------------------------------------
     String_PAN:WideString;
     String_EWK:WideString;
     MkIdx: Integer;



procedure AddFieldDescription(field_id:Integer; stlist:TStrings;defvalue:String='*');
var i:Integer;
begin
 if field_id>99 then exit;
 if field_id<0 then exit;
 if str[field_id]=nil then exit;

 stlist.Clear;

 if defvalue='*' then
  stlist.AddStrings(str[field_id])
 else
  begin
    for i:=0 to str[field_id].Count-2 do
      stlist.Add(str[field_id].Strings[i]);
    stlist.Add(defvalue);
  end;
end;

// заполняем массив значений полей для начинающейся транзакции
// получает введенные в TransGrid значения для полей из колонки 'Запрос' в FieldsStr
function TForm1.GetValueFields:Boolean;
var i,j,k:Integer;
    s:String;
begin
 Result:=false;

 for i:=0 to 99 do
  begin
   FieldsStr[i]:='';
   ResponseFields[i]:='';
  end;

 j:=TransGrid.RowCount-1;

 if j=1 then exit;

 for i:=1 to j do
  begin
     s:=TransGrid.Cells[0,i];
     k:=StrToInt(s);   // get num field

     s:=TransGrid.Cells[3,i];

     if s<>' ' then
       if s<>'X' then
        FieldsStr[k]:=s;
  end;
  Result:=true;
end;


// устанавливает значения полей в колонке 'Ответ' в TransGrid из ResponseFields
procedure TForm1.SetValueFields;
var i,j,k:Integer;
    s:String;
begin

 j:=TransGrid.RowCount-1;
 if j=1 then exit;

 for i:=1 to j do
  begin
     s:=TransGrid.Cells[0,i];
     k:=StrToInt(s);   // get num field

     s:=ResponseFields[k];
     TransGrid.Cells[4,i]:=s;
  end;
end;

// получает значение поля по номеру из  FieldsStr
function TForm1.GetField(id:Integer):String;
begin
 Result:='';
 if id>99 then exit;
 if id<0 then exit;

 Result:=FieldsStr[id];
end;

// устанавливает значение поля по номеру в ResponseFields
procedure TForm1.SetField(id:Integer;value:String);
begin

 if id>99 then exit;
 if id<0 then exit;

 if value <> '' then
  if value <> ' ' then
   if value <>'X' then
    ResponseFields[id]:=value;
end;

function TForm1.CheckExec:Boolean;
begin
Result:=true;

try
 if not Assigned(Exec) then
  begin
    Exec:=CreateComObject(CLASS_TransSender) as TransSender;
    {Инициализируем ресурсы}
    Exec.InitResources();

   if not Assigned(WrapperExec) then
    begin
      WrapperExec:=TTransSender.Create(nil);
      WrapperExec.ConnectTo(Exec);
      WrapperExec.OnExchange:=ContactLess_EventHandle;
      WrapperExec.OnPINExchange:=ContactLess_PinEventHandle;
    end
  end
except
      Result:=false;
end;        

end;

function TForm1.ExecTransaction(timeout:Integer; useEvent:Boolean):Boolean;
const rf: TReplaceFlags=[rfReplaceAll];

var   Request, Response,DummyData:TransData;
      s:String;
      ret:Integer;
begin

Result:=false;

{Создаем объекты Запрос, Ответ, Соединение с LanPOST}
try

  if not CheckExec then
   raise Exception.Create('Check TransSender error');

  Request:=CreateComObject(CLASS_TransData) as TransData;
  Response:=CreateComObject(CLASS_TransData) as TransData;
  DummyData:=nil;


except

    on Exc: Exception do
     begin
      Application.MessageBox(PChar(Exc.Message),'Ошибка');  exit;
     end;

end;


 try
{Формируем запрос}

s:=GetField(0);  if s<>'' then Request.Amount:=s;
s:=GetField(4);  if s<>'' then Request.CurrencyCode:=s;
s:=GetField(6);  if s<>'' then Request.DateTimeHost:=s;
s:=GetField(8);  if s<>'' then Request.CardEntryMode:=StrToInt(s); // POSEntryMode
s:=GetField(10); if s<>'' then Request.PAN:=s;
s:=GetField(11); if s<>'' then Request.CardExpiryDate:=s;
s:=GetField(12); if s<>'' then Request.TRACK2:=s;
s:=GetField(13); if s<>'' then Request.AuthorizationCode:=s;
s:=GetField(14); if s<>'' then Request.ReferenceNumber:=s;        //RRN
s:=GetField(15); if s<>'' then Request.ResponseCodeHost:=s;
s:=GetField(19); if s<>'' then Request.TextResponse:=s;
s:=GetField(21); if s<>'' then Request.DateTimeCRM:=s;            //TerminalDateTime
s:=GetField(23); if s<>'' then Request.TrxID:=StrToInt(s);        // STAN
s:=GetField(25); if s<>'' then Request.OperationCode:=StrToInt(s);
s:=GetField(26); if s<>'' then Request.TrxIDCRM:=StrToInt(s);      // STAN
s:=GetField(27); if s<>'' then Request.CRMID:=s;                   // логический TerminalID
s:=GetField(34); if s<>'' then Request.OrigOperation:=StrToInt(s);
s:=GetField(39); if s<>'' then Request.Status:=StrToInt(s);
s:=GetField(47); if s<>'' then Request.CVV2:=s;
s:=GetField(53); if s<>'' then Request.ProcessingFlag:=StrToInt(s);
s:=GetField(54); if s<>'' then Request.TrxIDHost:=StrToInt(s);
s:=GetField(80); if s<>'' then Request.CommodityCode:=s;
s:=GetField(81); if s<>'' then Request.PaymentDetails:=s;           // PaymentText
s:=GetField(82); if s<>'' then Request.ProviderCode:=s;
s:=GetField(89); if s<>'' then Request.PathParameters:=s;
s:=GetField(90); if s<>'' then Request.ReceiptData:=s;                  // EMV tags
s:=GetField(92); if s<>'' then Request.AmountFee:=s;                  // комиссия(скидка)
s:=GetField(93); if s<>'' then Request.TerminalOutID:=s;             // Host Terminal ID




{Устанавливаем параметры соединения с PC POST }
Exec.SetChannelParam(PostIp.Text, StrToInt(PostPort.Text),'');



{Запуск progressbar form}
ExecForm.FullTime:=timeout;
ExecForm.Show;
Timer1.Enabled:=true;

{Выполняем запрос}

{ выполнение c использованием событий только для contactless card entry mode}

if ((Request.CardEntryMode=7) and useEvent )  then
 begin

  ret:=WrapperExec.Exchange(Request, DummyData, timeout);

  // эмулируем неопределенный статус, если запрос на чтение contactless карты передан серверу LanPOST успешно
  // иначе сразу ошибка 
  if ret=0 then  Response.Status:=0
  else           Response.Status:=53;

 end
else
 begin
         {синхронное выполнение (без событий)}
  ret:=Exec.Exchange(Request, Response, timeout);

 end;

 ExecForm.Hide;      // скрыли progressbar form

{Получаем результат выполнения}
case Response.Status of
  0: TransResult := 'Неопределённый статус';

  1: begin TransResult := 'Одобрено'; Result:=true; end;

  8: TransResult := '	Ошибка при работе с картой';
  9: TransResult := '	Устройство недоступно';
  10:	TransResult := 'Ошибка конфигурации устройства';
  11:	TransResult := 'Отсутствие (ошибка) файла (ов) параметров';
  12:	TransResult := 'Неизвестное (неверное) значение параметра(ов)';
  13: TransResult := 'Аварийная отмена';
  16: TransResult := 'Отказано';
  17: TransResult := 'Выполнено в OFFLINE';
  34: TransResult := 'Нет соединения';
  53: TransResult := 'Операция прервана';
end;

if (((Response.Status=0) or (Response.Status=34)) and  (ExecForm.FullTime<=0)) then
 TransResult:= 'Превышено время ожидания'
else
if ( (Response.Status<>34) and (Response.Status<>53)) then
 begin
s:=Response.Amount;                        SetField(0,s);
s:=Response.CurrencyCode;                  SetField(4,s);
s:=Response.DateTimeHost;                  SetField(6,s);
s:=IntToStr(Response.CardEntryMode);       SetField(8,s);
s:=Response.PAN;                           SetField(10,s);
s:=Response.CardExpiryDate;                SetField(11,s);
s:=Response.TRACK2;                        SetField(12,s);
s:=Response.AuthorizationCode;             SetField(13,s);
s:=Response.ReferenceNumber;               SetField(14,s);
s:=Response.ResponseCodeHost;              SetField(15,s);
s:=Response.DateTimeCRM;                   SetField(21,s);
s:=IntToStr(Response.TrxID);               SetField(23,s);
s:=IntToStr(Response.OperationCode);       SetField(25,s);
s:=IntToStr(Response.TrxIDCRM);            SetField(26,s);
s:=Response.CRMID;                         SetField(27,s);
s:=IntToStr(Response.OrigOperation);       SetField(34,s);
s:=Response.CVV2;                          SetField(47,s);
s:=IntToStr(Response.ProcessingFlag);      SetField(53,s);
s:=IntToStr(Response.TrxIDHost);           SetField(54,s);
s:=Response.CommodityCode;                 SetField(80,s);
s:=Response.PaymentDetails;                SetField(81,s);
s:=Response.ProviderCode;                  SetField(82,s);
s:=Response.PathParameters;                SetField(89,s);
s:=Response.ReceiptData;                   begin  s:=StringReplace(s,'~','~'+#13#10,rf);  SetField(90,s); end;
s:=Response.AmountFee;                     SetField(92,s);
s:=Response.TerminalOutID;                 SetField(93,s);
end;

s:=Response.TextResponse;                  SetField(19,s);
s:=IntToStr(Response.Status);              SetField(39,s);

if Exec.ErrorCode >0 then
begin
 s:=Exec.ErrorDescription+' ( '+TransResult+' )';
 Application.MessageBox(PChar(s),'Ошибка при проведении транзакции');
end;

finally

{Уничтожаем объекты}
  Response:=nil;//Unassigned;
  Request:=nil;//Unassigned;

end;

end;


procedure TForm1.FormCreate(Sender: TObject);
var bb:Boolean; i:Integer;
begin
startloop:=false;
 with OpsGrid do
  begin
    Cells[0,0] := '  Код';
    Cells[1,0] := '  Название операции';

    Cells[0,1] := '  1';
    Cells[1,1] := ' Оплата';
    Cells[0,2] := '  2';
    Cells[1,2] := ' Снятие наличных'; // Выдача наличных
    Cells[0,3] := '  4';
    Cells[1,3] := ' Отмена';
    Cells[0,4] := ' 29';
    Cells[1,4] := ' Возврат';
    Cells[0,5] := ' 61';
    Cells[1,5] := ' Платеж (коммунальный)';
    Cells[0,6] := ' 62';
    Cells[1,6] := ' Платеж в сторону 3-х лиц';
    Cells[0,7] := ' 26';
    Cells[1,7] := ' Проверка соединения';
    Cells[0,8] := ' 13';
    Cells[1,8] := ' Баланс';
    Cells[0,9] := ' 59';
    Cells[1,9] := ' Сверка итогов';
    Cells[0,10] := ' 3';
    Cells[1,10] := ' Пополнение счета (Кредит)';
    Cells[0,11] := ' 71';
    Cells[1,11] := ' Кредит ваучер';
    Cells[0,12] := '  4';
    Cells[1,12] := ' Отмена кредита';
    Cells[0,13] := ' 79';
    Cells[1,13] := ' Получение параметров';
    Cells[0,14] := ' 80';
    Cells[1,14] := ' Загрузка параметров';

  end;

 TransResult:='Отсутствует';
 useSelForm:=false;
 useSelDir:=false;
 useSelCurrency:=false;
 TransGrid.Columns[0].Width:=0;

 for i:=0 to 99 do
  begin
   str[i]:=TStringList.Create();
   str[i].Add(IntToStr(i));

   case i of
    0: begin
        str[i].Add('Сумма операции, выраженная в минимальных единицах валюты'); str[i].Add('Amount'); str[i].Add('1001');
       end;
    4: begin
        str[i].Add('Код валюты операции'); str[i].Add('CurrencyCode'); str[i].Add('643');
       end;
    6: begin
        str[i].Add('Ориг. дата и время совершения операции (YYYYMMDDHHMMSS) на хосте'); str[i].Add('TransDateTime'); str[i].Add(' ');
       end;
    8: begin
        str[i].Add('Способ ввода карты'); str[i].Add('CardEntryMode'); str[i].Add('7');
       end;
    10: begin
        str[i].Add('Номер карты'); str[i].Add('PAN'); str[i].Add(' ');
       end;
    11: begin
        str[i].Add('Срок действия карты (YYMM)'); str[i].Add('CardExpiryDate'); str[i].Add(' ');
       end;
    12: begin
        str[i].Add('Данные Track2'); str[i].Add('TRACK2'); str[i].Add(' ');
       end;
    13: begin
        str[i].Add('Код авторизации'); str[i].Add('AuthorizationCode'); str[i].Add(' ');
       end;
    14: begin
        str[i].Add('Номер ссылки'); str[i].Add('ReferenceNumber (RRN)'); str[i].Add(' ');
       end;
    15: begin
        str[i].Add('Код ответа хоста'); str[i].Add('ResponseCodeHost'); str[i].Add(' ');
       end;
    19: begin
        str[i].Add('Дополнительные данные ответа'); str[i].Add('TextResponse'); str[i].Add(' ');
       end;
    21: begin
        str[i].Add('Ориг. дата и время совершения операции (YYYYMMDDHHMMSS) на пинпаде'); str[i].Add('TerminalDateTime'); str[i].Add(' ');
       end;
    23: begin
        str[i].Add('Идентификатор транзакции в коммуникационном сервере'); str[i].Add('TrxID (STAN)'); str[i].Add(' ');
       end;
    25: begin
        str[i].Add('Код операции'); str[i].Add('OperationCode'); str[i].Add(' ');
       end;
    26: begin
        str[i].Add('Уникальный номер транзакции на пинпаде'); str[i].Add('TrxIDCRM (STAN)'); str[i].Add(' ');
       end;
    27: begin
//        str[i].Add('Идентификатор пинпада'); str[i].Add('CRMID (TMS TerminalID)'); {str[i].Add('T0000001');} str[i].Add('00000001');
        str[i].Add('Логический номер терминала'); str[i].Add('CRMID (LogicalTerminalID)'); {str[i].Add('T0000001');} str[i].Add('00000001');
       end;
    34: begin
        str[i].Add('Код оригинальной операции'); str[i].Add('OrigOperationCode'); str[i].Add('X');
       end;
    39: begin
        str[i].Add('Статус выполнения транзакции'); str[i].Add('Status'); str[i].Add(' ');
       end;
    47: begin
        str[i].Add('CVV2 карты'); str[i].Add('CVV2'); str[i].Add(' ');//str[i].Add('X');
       end;
    53: begin
        str[i].Add('Флаг обработки операции'); str[i].Add('ProcessingFlag'); str[i].Add(' ');
       end;
    54: begin
        str[i].Add('Уникальный номер транзакции на хосте авторизации'); str[i].Add('TrxIDHost'); str[i].Add(' ');
       end;
    80: begin
        str[i].Add('Код платежа'); str[i].Add('CommodityCode'); str[i].Add('X');
       end;
    81: begin
        str[i].Add('Детали платежа'); str[i].Add('PaymentDetails'); str[i].Add('X');
       end;
    82: begin
        str[i].Add('Код провайдера '); str[i].Add('ProviderCode'); str[i].Add('X');
       end;
    89: begin
        str[i].Add('Путь к файлам параметров '); str[i].Add('PathParameters'); str[i].Add(LastDir);
       end;

    90: begin
        str[i].Add('Данные для печати на чеке '); str[i].Add('ReceiptData'); str[i].Add(' ');
       end;

    92: begin
        str[i].Add('Сумма комиссии (скидки) по операции'); str[i].Add('AmountFee'); str[i].Add(' ');
       end;
    93: begin
        str[i].Add('Идентификатор терминала на хосте'); str[i].Add('TerminalOutID'); str[i].Add(' ');
       end;
    else
     begin
       str[i].Free;
       str[i]:=nil;
     end;

   end;
  end;

OpsGridSelectCell(Sender,1,1,bb);
TransGridSelectCell(Sender,4,1,bb);


end;

procedure TForm1.EditCellEditor1ElipsisClick(Sender: TObject);
var
  i:Integer;
  s: string;
begin

 if useSelForm then
  begin
   i:=POSEntrySelect.ShowModal;
   if i=mrOK then
    begin
     EditCellEditor1.Editor.Text :=IntToStr(POSEntrySelect.RG.ItemIndex);
    end;
   LastEntryMode:=StrToInt(EditCellEditor1.Editor.Text);
 end
 else
  if useSelDir then
   begin
      if LastDir<>'X' then
       FB.Folder:=LastDir
      else
       FB.Folder:=ExtractFilePath(Application.ExeName);

      if FB.Execute then
       begin
         if Length(FB.Folder)>127 then
          begin
           Application.MessageBox('Длина пути к файлам параметров превышает 128 байт!','Ошибка',MB_OK);
           exit;
          end;
         LastDir:=FB.Folder;
         EditCellEditor1.Editor.Text :=FB.Folder;
       end;
   end
    else
     if useSelCurrency then
      begin
      i:=CurrencySelect.ShowModal;
      if i=mrOK then
       begin
        if CurrencySelect.RG.ItemIndex = 0 then
          EditCellEditor1.Editor.Text :='643'
        else
        if CurrencySelect.RG.ItemIndex = 1 then
          EditCellEditor1.Editor.Text :='840'
        else
          if CurrencySelect.RG.ItemIndex = 2 then
          EditCellEditor1.Editor.Text :='978';

          CurrencySelect.RG.ItemIndex:=0;
       end;

     end
  else
   begin
     with TransGrid do begin
       s := EditCellEditor1.Editor.Text;
       if s='X' then s:='';
      if InputQuery('Введите новое значение поля', 'Исходное значение: "'+s+'"', s) then
        EditCellEditor1.Editor.Text := s;
     end;

    end;

end;


procedure TForm1.OpsGridSelectCell(Sender: TObject; ACol, ARow: Integer; var CanSelect: Boolean);
var i,j:Integer;
begin

 CanSelect:=true;
 j:=TransGrid.RowCount-1;

 for i:=1 to j do
   TransGrid.Rows[i].Clear;

 TransGrid.RowHeights[0]:=30;
 i:=1;

 if POSEntrySelect<>nil then
  POSEntrySelect.RG.ItemIndex:=3;
 
case ARow   of
 1:  // Оплата
     with TransGrid do
      begin
        AddFieldDescription(0,Rows[i]); Inc(i);
        AddFieldDescription(4,Rows[i]); Inc(i);
        AddFieldDescription(6,Rows[i]); Inc(i);
        AddFieldDescription(8,Rows[i]); Inc(i);
        AddFieldDescription(10,Rows[i]); Inc(i);
        AddFieldDescription(11,Rows[i]); Inc(i);
//        AddFieldDescription(12,Rows[i]); Inc(i);
        AddFieldDescription(13,Rows[i]); Inc(i);
        AddFieldDescription(14,Rows[i]); Inc(i);
        AddFieldDescription(15,Rows[i]); Inc(i);
        AddFieldDescription(19,Rows[i]); Inc(i);
        AddFieldDescription(21,Rows[i]); Inc(i);
        AddFieldDescription(23,Rows[i]); Inc(i);
        AddFieldDescription(25,Rows[i],'1'); Inc(i);
        AddFieldDescription(26,Rows[i]); Inc(i);
        AddFieldDescription(27,Rows[i]); Inc(i);
        AddFieldDescription(39,Rows[i]); Inc(i);
        AddFieldDescription(47,Rows[i]); Inc(i);
        AddFieldDescription(90,Rows[i]); Inc(i);
        AddFieldDescription(92,Rows[i]); Inc(i);
        AddFieldDescription(93,Rows[i]); Inc(i);
      end;

 2:    //Выдача наличных

     with TransGrid do
      begin
        AddFieldDescription(0,Rows[i]); Inc(i);
        AddFieldDescription(4,Rows[i]); Inc(i);
        AddFieldDescription(6,Rows[i]); Inc(i);
        AddFieldDescription(8,Rows[i]); Inc(i);
        AddFieldDescription(10,Rows[i]); Inc(i);
        AddFieldDescription(11,Rows[i]); Inc(i);
//        AddFieldDescription(12,Rows[i]); Inc(i);
        AddFieldDescription(13,Rows[i]); Inc(i);
        AddFieldDescription(14,Rows[i]); Inc(i);
        AddFieldDescription(15,Rows[i]); Inc(i);
        AddFieldDescription(19,Rows[i]); Inc(i);
        AddFieldDescription(21,Rows[i]); Inc(i);
        AddFieldDescription(23,Rows[i]); Inc(i);
        AddFieldDescription(25,Rows[i],'2'); Inc(i);
        AddFieldDescription(26,Rows[i]); Inc(i);
        AddFieldDescription(27,Rows[i]); Rows[i].Strings[3]:='00000002';Inc(i);
        AddFieldDescription(39,Rows[i]); Inc(i);
        AddFieldDescription(47,Rows[i]); Inc(i);
        AddFieldDescription(90,Rows[i]); Inc(i);
        AddFieldDescription(92,Rows[i]); Inc(i);
        AddFieldDescription(93,Rows[i]); Inc(i);

      end;

 3:    //Отмена операции

     with TransGrid do
      begin
        AddFieldDescription(0,Rows[i]);Rows[i].Strings[3]:='X'; Inc(i);
        AddFieldDescription(4,Rows[i]); Inc(i);
        AddFieldDescription(6,Rows[i]); Inc(i);
        AddFieldDescription(8,Rows[i]); Inc(i);
        AddFieldDescription(10,Rows[i]); Inc(i);
        AddFieldDescription(11,Rows[i]); Inc(i);
//        AddFieldDescription(12,Rows[i]); Inc(i);
        AddFieldDescription(13,Rows[i]); Rows[i].Strings[3]:='X'; Inc(i);
        AddFieldDescription(14,Rows[i]); {Rows[i].Strings[3]:='X';} Inc(i);


        AddFieldDescription(15,Rows[i]); Inc(i);
        AddFieldDescription(19,Rows[i]); Inc(i);
        AddFieldDescription(21,Rows[i]); Inc(i);
        AddFieldDescription(23,Rows[i]); Inc(i);
        AddFieldDescription(25,Rows[i],'4'); Inc(i);
        AddFieldDescription(26,Rows[i]); Inc(i);
        AddFieldDescription(27,Rows[i]); Inc(i);
        AddFieldDescription(34,Rows[i]); Inc(i);
        AddFieldDescription(39,Rows[i]); Inc(i);
        AddFieldDescription(90,Rows[i]); Inc(i);
        AddFieldDescription(92,Rows[i]); Inc(i);
        AddFieldDescription(93,Rows[i]); Inc(i);
      end;


 4: //  Возврат
      with TransGrid do
      begin
        AddFieldDescription(0,Rows[i]); Rows[i].Strings[3]:='X';Inc(i);
        AddFieldDescription(4,Rows[i]);Inc(i);
        AddFieldDescription(6,Rows[i]); Inc(i);
        AddFieldDescription(8,Rows[i]); Inc(i);
        AddFieldDescription(10,Rows[i]); Inc(i);
        AddFieldDescription(11,Rows[i]); Inc(i);
//        AddFieldDescription(12,Rows[i]); Inc(i);
        AddFieldDescription(13,Rows[i]); {Rows[i].Strings[3]:='X';} Inc(i);
        AddFieldDescription(14,Rows[i]);Rows[i].Strings[3]:='X'; Inc(i);
        AddFieldDescription(15,Rows[i]); Inc(i);
        AddFieldDescription(19,Rows[i]); Inc(i);
        AddFieldDescription(21,Rows[i]); Inc(i);
        AddFieldDescription(23,Rows[i]); Inc(i);
        AddFieldDescription(25,Rows[i],'29'); Inc(i);
        AddFieldDescription(26,Rows[i]); Inc(i);
        AddFieldDescription(27,Rows[i]); Inc(i);
//        AddFieldDescription(34,Rows[i]); Inc(i);     // на возврате это поле отсутствует
        AddFieldDescription(39,Rows[i]); Inc(i);
        AddFieldDescription(47,Rows[i]); Inc(i);
        AddFieldDescription(90,Rows[i]); Inc(i);
        AddFieldDescription(92,Rows[i]); Inc(i);
        AddFieldDescription(93,Rows[i]); Inc(i);
      end;

 5: //  Платеж
      with TransGrid do
      begin
        AddFieldDescription(0,Rows[i]); Inc(i);
        AddFieldDescription(4,Rows[i]); Inc(i);
        AddFieldDescription(6,Rows[i]); Inc(i);
        AddFieldDescription(8,Rows[i]); Inc(i);
        AddFieldDescription(10,Rows[i]); Inc(i);
        AddFieldDescription(11,Rows[i]); Inc(i);
  //      AddFieldDescription(12,Rows[i]); Inc(i);
        AddFieldDescription(13,Rows[i]); Inc(i);
        AddFieldDescription(14,Rows[i]); Inc(i);
        AddFieldDescription(15,Rows[i]); Inc(i);
        AddFieldDescription(19,Rows[i]); Inc(i);
        AddFieldDescription(21,Rows[i]); Inc(i);
        AddFieldDescription(23,Rows[i]); Inc(i);
        AddFieldDescription(25,Rows[i],'61'); Inc(i);
        AddFieldDescription(26,Rows[i]); Inc(i);
        AddFieldDescription(27,Rows[i]); Inc(i);
        AddFieldDescription(39,Rows[i]); Inc(i);
        AddFieldDescription(47,Rows[i]); Inc(i);
        AddFieldDescription(80,Rows[i]); Inc(i);
        AddFieldDescription(81,Rows[i]); Inc(i);
        AddFieldDescription(90,Rows[i]); Inc(i);
        AddFieldDescription(92,Rows[i]); Inc(i);
        AddFieldDescription(93,Rows[i]); Inc(i);
      end;

 6: //  Платеж в сторону 3-х лиц
      with TransGrid do
      begin
        AddFieldDescription(0,Rows[i]); Inc(i);
        AddFieldDescription(4,Rows[i]); Inc(i);
        AddFieldDescription(6,Rows[i]); Inc(i);
        AddFieldDescription(8,Rows[i]); Inc(i);
        AddFieldDescription(10,Rows[i]); Inc(i);
        AddFieldDescription(11,Rows[i]); Inc(i);
//        AddFieldDescription(12,Rows[i]); Inc(i);
        AddFieldDescription(13,Rows[i]); Inc(i);
        AddFieldDescription(14,Rows[i]); Inc(i);
        AddFieldDescription(15,Rows[i]); Inc(i);
        AddFieldDescription(19,Rows[i]); Inc(i);
        AddFieldDescription(21,Rows[i]); Inc(i);
        AddFieldDescription(23,Rows[i]); Inc(i);
        AddFieldDescription(25,Rows[i],'62'); Inc(i);
        AddFieldDescription(26,Rows[i]); Inc(i);
        AddFieldDescription(27,Rows[i]); Inc(i);
        AddFieldDescription(39,Rows[i]); Inc(i);
        AddFieldDescription(47,Rows[i]); Inc(i);
        AddFieldDescription(80,Rows[i]); Inc(i);
        AddFieldDescription(81,Rows[i]); Inc(i);
        AddFieldDescription(82,Rows[i]); Inc(i);
        AddFieldDescription(90,Rows[i]); Inc(i);
        AddFieldDescription(92,Rows[i]); Inc(i);
        AddFieldDescription(93,Rows[i]); Inc(i);
      end;


 7: //  Проверка соединения
      with TransGrid do
      begin
        AddFieldDescription(15,Rows[i]); Inc(i);
        AddFieldDescription(19,Rows[i]); Inc(i);
        AddFieldDescription(25,Rows[i],'26'); Inc(i);
        AddFieldDescription(27,Rows[i]); Inc(i);
        AddFieldDescription(39,Rows[i]); Inc(i);
        AddFieldDescription(93,Rows[i]); Inc(i);
      end;

 8: //  Баланс
      with TransGrid do
      begin

        AddFieldDescription(0,Rows[i],' ');Inc(i);
        AddFieldDescription(4,Rows[i]); Inc(i);
        AddFieldDescription(6,Rows[i]); Inc(i);
        AddFieldDescription(8,Rows[i]); Inc(i);
        AddFieldDescription(10,Rows[i]); Inc(i);
        AddFieldDescription(11,Rows[i]); Inc(i);
//        AddFieldDescription(12,Rows[i]); Inc(i);
//        AddFieldDescription(13,Rows[i]); Inc(i);
        AddFieldDescription(15,Rows[i]); Inc(i);
        AddFieldDescription(19,Rows[i]); Inc(i);
        AddFieldDescription(21,Rows[i]); Inc(i);
        AddFieldDescription(23,Rows[i]); Inc(i);
        AddFieldDescription(25,Rows[i],'13'); Inc(i);
        AddFieldDescription(26,Rows[i]); Inc(i);
        AddFieldDescription(27,Rows[i]); Inc(i);
        AddFieldDescription(39,Rows[i]); Inc(i);
        AddFieldDescription(47,Rows[i]); Inc(i);
        AddFieldDescription(54,Rows[i]); Inc(i);
        AddFieldDescription(90,Rows[i]); Inc(i);
        AddFieldDescription(92,Rows[i]); Inc(i);
        AddFieldDescription(93,Rows[i]); Inc(i);
      end;


 9: //  Сверка итогов (Settlement)
      with TransGrid do
      begin

        AddFieldDescription(15,Rows[i]); Inc(i);
        AddFieldDescription(19,Rows[i]); Inc(i);
        AddFieldDescription(21,Rows[i]); Inc(i);
        AddFieldDescription(25,Rows[i],'59'); Inc(i);
        AddFieldDescription(26,Rows[i]); Inc(i);
        AddFieldDescription(27,Rows[i]); Inc(i);
        AddFieldDescription(39,Rows[i]); Inc(i);
        AddFieldDescription(93,Rows[i]); Inc(i);
      end;

 10: //  Кредит
      with TransGrid do
      begin

        AddFieldDescription(0,Rows[i]); Inc(i);
        AddFieldDescription(4,Rows[i]); Inc(i);
        AddFieldDescription(6,Rows[i]); Inc(i);
        AddFieldDescription(8,Rows[i]); Inc(i);
        AddFieldDescription(10,Rows[i]); Inc(i);
        AddFieldDescription(11,Rows[i]); Inc(i);
//        AddFieldDescription(12,Rows[i]); Inc(i);

        AddFieldDescription(14,Rows[i]); Inc(i);
        AddFieldDescription(15,Rows[i]); Inc(i);
        AddFieldDescription(19,Rows[i]); Inc(i);
        AddFieldDescription(21,Rows[i]); Inc(i);
        AddFieldDescription(23,Rows[i]); Inc(i);
        AddFieldDescription(25,Rows[i],'3'); Inc(i);
        AddFieldDescription(26,Rows[i]); Inc(i);
        AddFieldDescription(27,Rows[i]);Rows[i].Strings[3]:='00000002'; Inc(i);
        AddFieldDescription(39,Rows[i]); Inc(i);
        AddFieldDescription(53,Rows[i]); Inc(i);
        AddFieldDescription(90,Rows[i]); Inc(i);
        AddFieldDescription(92,Rows[i]); Inc(i);
        AddFieldDescription(93,Rows[i]); Inc(i);
      end;


 11: //  Кредит ваучер
      with TransGrid do
      begin
        AddFieldDescription(0,Rows[i]); Inc(i);
        AddFieldDescription(4,Rows[i]); Inc(i);
        AddFieldDescription(6,Rows[i]); Inc(i);
        AddFieldDescription(8,Rows[i]); Inc(i);
        AddFieldDescription(10,Rows[i]); Inc(i);
        AddFieldDescription(11,Rows[i]); Inc(i);
//        AddFieldDescription(12,Rows[i]); Inc(i);
        AddFieldDescription(14,Rows[i]); Inc(i);
        AddFieldDescription(15,Rows[i]); Inc(i);
        AddFieldDescription(19,Rows[i]); Inc(i);
        AddFieldDescription(21,Rows[i]); Inc(i);
        AddFieldDescription(23,Rows[i]); Inc(i);
        AddFieldDescription(25,Rows[i],'71'); Inc(i);
        AddFieldDescription(26,Rows[i]);Rows[i].Strings[3]:='X'; Inc(i);
//        AddFieldDescription(26,Rows[i]); Inc(i);
        AddFieldDescription(27,Rows[i]); Rows[i].Strings[3]:='00000002';Inc(i);
        AddFieldDescription(39,Rows[i]); Inc(i);
        AddFieldDescription(53,Rows[i]); Inc(i);
        AddFieldDescription(90,Rows[i]); Inc(i);
        AddFieldDescription(92,Rows[i]); Inc(i);
        AddFieldDescription(93,Rows[i]); Inc(i);
      end;

 12:    //Отмена кредита

     with TransGrid do
      begin
        AddFieldDescription(0,Rows[i]); Rows[i].Strings[3]:='X'; Inc(i);
        AddFieldDescription(4,Rows[i]); Inc(i);
        AddFieldDescription(6,Rows[i]); Inc(i);
        AddFieldDescription(8,Rows[i]); Inc(i);
        AddFieldDescription(10,Rows[i]); Inc(i);
        AddFieldDescription(11,Rows[i]); Inc(i);
//      AddFieldDescription(12,Rows[i]); Inc(i);

         AddFieldDescription(13,Rows[i]); Rows[i].Strings[3]:='X'; Inc(i);
        AddFieldDescription(14,Rows[i]); {Rows[i].Strings[3]:='X';} Inc(i);

        AddFieldDescription(15,Rows[i]); Inc(i);
        AddFieldDescription(19,Rows[i]); Inc(i);
//      AddFieldDescription(21,Rows[i]); Inc(i);
        AddFieldDescription(23,Rows[i]); Inc(i);
        AddFieldDescription(25,Rows[i],'4'); Inc(i);
        AddFieldDescription(26,Rows[i]); Inc(i);
        AddFieldDescription(27,Rows[i]); Rows[i].Strings[3]:='00000002'; Inc(i);
        AddFieldDescription(34,Rows[i]); Rows[i].Strings[3]:='3';Inc(i);
        AddFieldDescription(39,Rows[i]); Inc(i);
        AddFieldDescription(90,Rows[i]); Inc(i);
        AddFieldDescription(92,Rows[i]); Inc(i);
        AddFieldDescription(93,Rows[i]); Inc(i);
      end;


 13: //  Получение параметров  ( c глобального TMS в локальный каталог)
      with TransGrid do
      begin
        AddFieldDescription(19,Rows[i]); Inc(i);
        AddFieldDescription(25,Rows[i],'79'); Inc(i);
        AddFieldDescription(39,Rows[i]); Inc(i);
        AddFieldDescription(89,Rows[i]); Inc(i);
      end;


 14: //  Загрузка параметров   ( c локального TMS)
      with TransGrid do
      begin
        AddFieldDescription(19,Rows[i]); Inc(i);
        AddFieldDescription(25,Rows[i],'80'); Inc(i);
        AddFieldDescription(39,Rows[i]); Inc(i);
        AddFieldDescription(89,Rows[i]); Inc(i);
      end;


   else
    i:=2;

 end;
        TransGrid.RowCount:=i;
end;

procedure TForm1.FormDestroy(Sender: TObject);
var i:Integer;
begin
for i:=0 to 99 do
  begin
   if str[i]<> nil then
    str[i].Free;
  end;

{Освобождаем ресурсы}

 if Assigned(WrapperExec) then
  begin
   WrapperExec.Disconnect;
   WrapperExec:=nil;
  end;

 if Assigned(Exec) then
  begin
   Exec.FreeResources();
   Exec:=nil;
  end;


end;

procedure TForm1.BtnExecClick(Sender: TObject);
begin
 TransResult:='Отсутствует';
 ResultLabel.Caption:=TransResult;
 RetryCount:=0;
 
 if GetValueFields then
  begin
    SetValueFields;
    Application.ProcessMessages;
    ExecTransaction(TimeRun.Value,UseEvents.Checked);
    SetValueFields;
 end;

 ResultLabel.Caption:=TransResult;
end;

procedure TForm1.TransGridSelectCell(Sender: TObject; ACol, ARow: Integer; var CanSelect: Boolean);
var s1:String; i:Integer;
begin

CanSelect:=false;
useSelForm:=false;
useSelDir:=false;
useSelCurrency:=false;


if ARow<=0 then exit;
if ACol<3 then exit;

CanSelect:=true;

if ACol=4 then exit;

s1:=TransGrid.Cells[3,ARow];  // check must field present in request
if s1=' ' then CanSelect:=false; //         ('-/X','M/X' and 'O/X')

i:=0;
s1:=TransGrid.Cells[0,ARow]; // get field code

if s1<>'' then
 begin
   i:=StrToInt(s1);

   if i=25 then CanSelect:=false;

   if((i=10) or (i=11)) then
    if( (LastEntryMode>1) and (LastEntryMode<>6) ) then
    begin
       CanSelect:=false;  // PAN and Expired date entered только при ручном вводе, неопределенном, и оплате наличными
       TransGrid.Cells[3,ARow]:=' ';
     end
    else
    begin
       if Length(TransGrid.Cells[3,ARow])<2 then
         TransGrid.Cells[3,ARow]:='X';
    end;

 end;

if ACol=3 then
 begin

  if i=4 then  // Currency
     useSelCurrency:=true
  else
    if i=8 then  // POSEntryMode
     useSelForm:=true
    else
      if i=89 then  // PathDir
       useSelDir:=true;
 end;

end;

procedure TForm1.TransGridMouseDown(Sender: TObject; Button: TMouseButton;
  Shift: TShiftState; X, Y: Integer);
var ACol,ARow:Integer;
    p:TPoint;

begin
if Button<>mbRight then exit;
TransGrid.MouseToCell(X,Y,ACol,ARow);

if ACol<>4 then exit;
BufferString:=TransGrid.Cells[ACol,ARow];
p.X:=X; p.Y:=Y;
p:=ClientToScreen(p);
if BufferString<>'' then
  PopMenu.Popup(p.X+Panel1.Left,p.Y);
end;

procedure TForm1.N1Click(Sender: TObject);
begin
Clipboard.SetTextBuf(PChar(BufferString));
end;

procedure TForm1.Timer1Timer(Sender: TObject);
begin

Timer1.Enabled:=false;

if ExecForm.ModalResult=mrCancel then
 begin
     if Assigned(Exec) then
        Exec.CancelExchange;
 end
else
  Timer1.Enabled:=true;

end;

/////////////////    !!!!!!!!!!!!!!!!!!!!!!!!!!!! /////////////////////////////////////////////////////////////////////////////


procedure TForm1.Timer2Timer(Sender: TObject);
const rf: TReplaceFlags=[rfReplaceAll];
var       Response,DummyData:TransData;
          s:String;
begin

 Timer2.Enabled:=false;
 TransResult:='Отсутствует';
 ResultLabel.Caption:=TransResult;
 Application.ProcessMessages;

     if CheckExec then
       begin
        DummyData:=nil;
        Response:=CreateComObject(CLASS_TransData) as TransData;
        WrapperExec.Exchange(DummyData, Response,TimeRun.Value);
      end;

{Получаем результат выполнения}
case Response.Status of
  0: TransResult := 'Неопределённый статус';
  1: TransResult := 'Одобрено'; 

  8: TransResult := '	Ошибка при работе с картой';
  9: TransResult := '	Устройство недоступно';
  10:	TransResult := 'Ошибка конфигурации устройства';
  11:	TransResult := 'Отсутствие (ошибка) файла (ов) параметров';
  12:	TransResult := 'Неизвестное (неверное) значение параметра(ов)';
  13: TransResult := 'Аварийная отмена';
  16: TransResult := 'Отказано';
  17: TransResult := 'Выполнено в OFFLINE';
  34: TransResult := 'Нет соединения';
  53: TransResult := 'Операция прервана';
end;

if (((Response.Status=0) or (Response.Status=34)) ) then
 TransResult:= 'Превышено время ожидания'
else
if ( (Response.Status<>34) and (Response.Status<>53)) then
 begin
s:=Response.Amount;                        SetField(0,s);
s:=Response.CurrencyCode;                  SetField(4,s);
s:=Response.DateTimeHost;                  SetField(6,s);
s:=IntToStr(Response.CardEntryMode);       SetField(8,s);
s:=Response.PAN;                           SetField(10,s);
s:=Response.CardExpiryDate;                SetField(11,s);
s:=Response.TRACK2;                        SetField(12,s);
s:=Response.AuthorizationCode;             SetField(13,s);
s:=Response.ReferenceNumber;               SetField(14,s);
s:=Response.ResponseCodeHost;              SetField(15,s);
s:=Response.DateTimeCRM;                   SetField(21,s);
s:=IntToStr(Response.TrxID);               SetField(23,s);
s:=IntToStr(Response.OperationCode);       SetField(25,s);
s:=IntToStr(Response.TrxIDCRM);            SetField(26,s);
s:=Response.CRMID;                         SetField(27,s);
s:=IntToStr(Response.OrigOperation);       SetField(34,s);
s:=Response.CVV2;                          SetField(47,s);
s:=IntToStr(Response.ProcessingFlag);      SetField(53,s);
s:=IntToStr(Response.TrxIDHost);           SetField(54,s);
s:=Response.CommodityCode;                 SetField(80,s);
s:=Response.PaymentDetails;                SetField(81,s);
s:=Response.ProviderCode;                  SetField(82,s);
s:=Response.PathParameters;                SetField(89,s);
s:=Response.ReceiptData;                   begin  s:=StringReplace(s,'~','~'+#13#10,rf);  SetField(90,s); end;
s:=Response.AmountFee;                     SetField(92,s);
s:=Response.TerminalOutID;                 SetField(93,s);
end;

s:=Response.TextResponse;                  SetField(19,s);
s:=IntToStr(Response.Status);              SetField(39,s);

if Exec.ErrorCode >0 then
begin
 s:=Exec.ErrorDescription+' ( '+TransResult+' )';
 Application.MessageBox(PChar(s),'Ошибка при проведении транзакции');
end;


 SetValueFields;
 ResultLabel.Caption:=TransResult;

end;



procedure TForm1.ContactLess_EventHandle(Sender: TObject;Result:Integer);
begin
    if Result=1 then
     begin
      ResultLabel.Caption:='Event: Card read OK !';
      Timer2.Enabled:=true;
     end
    else
     begin
            {результат выполнения}
      case Result of
        0:  TransResult := 'Неопределённый статус';

        8:  begin            // отработка 3-х попыток чтения карты
              TransResult := 'Ошибка при работе с картой';
              if RetryCount<2 then
                Timer4.Enabled:=true;

            end;
        
        9:  TransResult := 'Устройство недоступно';
        10:	TransResult := 'Ошибка конфигурации устройства';
        11:	TransResult := 'Отсутствие (ошибка) файла (ов) параметров';
        12:	TransResult := 'Неизвестное (неверное) значение параметра(ов)';
        13: TransResult := 'Аварийная отмена';
        16: TransResult := 'Отказано';
        17: TransResult := 'Выполнено в OFFLINE';
        34: TransResult := 'Нет соединения';
        53: TransResult := 'Операция прервана';
      end;

      ResultLabel.Caption:=TransResult;
    end;

end;


function GetEncryptedPinBlock(const PAN:WideString;const EWK:WideString;MkIndex:Integer):String;
begin
//   Result:='5123AB677DE29174';    // просто подстава,
                                 //на самом деле должен быть запрошен ввод пин-кода на пин-клаве
                                 // и возвращенный пин-блок(8 байт), после перевода в ASCII HEX представление(16 байт)
                                 // должен быть возвращен из функции или пустая строка, в случае отказа от ввода пин-кода
  Result:='';
end;

// эта процедура вызывается только после успешного прочтения карты на ридере, при необходимости ввода PIN
// см. где - Timer3.Enabled:=True
procedure TForm1.Timer3Timer(Sender: TObject);
const rf: TReplaceFlags=[rfReplaceAll];
var       Response,DummyData:TransData;
          s:String;
          EncPBlock:WideString;
begin

 Timer3.Enabled:=false;
 TransResult:='Отсутствует';
 ResultLabel.Caption:=TransResult;
 Application.ProcessMessages;

     if CheckExec then
       begin
        DummyData:=nil;
        Response:=CreateComObject(CLASS_TransData) as TransData;

// здесь надо заполнить свойство EncPinBlock - hex-строковым представлением реального пин-блока, полученным на пин-клаве
       EncPBlock:=GetEncryptedPinBlock(String_PAN,String_EWK,MkIdx);
       Response.EncPinBlock:=EncPBlock;
       WrapperExec.Exchange(DummyData, Response,TimeRun.Value);
      end;

{Получаем результат выполнения}
case Response.Status of
  0: TransResult := 'Неопределённый статус';
  1: TransResult := 'Одобрено'; 

//  8: TransResult := '	Ошибка при работе с картой';           здесь этот код не может появиться
//  9: TransResult := '	Устройство недоступно';                здесь этот код не может появиться
//  10:	TransResult := 'Ошибка конфигурации устройства';       здесь этот код не может появиться
  11:	TransResult := 'Отсутствие (ошибка) файла (ов) параметров';
  12:	TransResult := 'Неизвестное (неверное) значение параметра(ов)';
  13: TransResult := 'Аварийная отмена';
  16: TransResult := 'Отказано';
  17: TransResult := 'Выполнено в OFFLINE';
  34: TransResult := 'Нет соединения';
  53: TransResult := 'Операция прервана';
end;

if (((Response.Status=0) or (Response.Status=34)) ) then
 TransResult:= 'Превышено время ожидания'
else
if ( (Response.Status<>34) and (Response.Status<>53)) then
 begin
s:=Response.Amount;                        SetField(0,s);
s:=Response.CurrencyCode;                  SetField(4,s);
s:=Response.DateTimeHost;                  SetField(6,s);
s:=IntToStr(Response.CardEntryMode);       SetField(8,s);
s:=Response.PAN;                           SetField(10,s);
s:=Response.CardExpiryDate;                SetField(11,s);
s:=Response.TRACK2;                        SetField(12,s);
s:=Response.AuthorizationCode;             SetField(13,s);
s:=Response.ReferenceNumber;               SetField(14,s);
s:=Response.ResponseCodeHost;              SetField(15,s);
s:=Response.DateTimeCRM;                   SetField(21,s);
s:=IntToStr(Response.TrxID);               SetField(23,s);
s:=IntToStr(Response.OperationCode);       SetField(25,s);
s:=IntToStr(Response.TrxIDCRM);            SetField(26,s);
s:=Response.CRMID;                         SetField(27,s);
s:=IntToStr(Response.OrigOperation);       SetField(34,s);
s:=Response.CVV2;                          SetField(47,s);
s:=IntToStr(Response.ProcessingFlag);      SetField(53,s);
s:=IntToStr(Response.TrxIDHost);           SetField(54,s);
s:=Response.CommodityCode;                 SetField(80,s);
s:=Response.PaymentDetails;                SetField(81,s);
s:=Response.ProviderCode;                  SetField(82,s);
s:=Response.PathParameters;                SetField(89,s);
s:=Response.ReceiptData;                   begin  s:=StringReplace(s,'~','~'+#13#10,rf);  SetField(90,s); end;
s:=Response.AmountFee;                     SetField(92,s);
s:=Response.TerminalOutID;                 SetField(93,s);
end;

s:=Response.TextResponse;                  SetField(19,s);
s:=IntToStr(Response.Status);              SetField(39,s);

if Exec.ErrorCode >0 then
begin
 s:=Exec.ErrorDescription+' ( '+TransResult+' )';
 Application.MessageBox(PChar(s),'Ошибка при проведении транзакции');
end;


 SetValueFields;
 ResultLabel.Caption:=TransResult;
end;

// эта процедура вызывается только в случае успешной работы с бесконтактной картой, когда она запрашивает online пин для авторизации
procedure TForm1.ContactLess_PinEventHandle(Sender: TObject;const PAN: WideString; const EWK: WideString; MkIndex: Integer);
begin
      ResultLabel.Caption:='Card OK. Need Enter PIN !';

// сохраняем параметры для дальнейшего использования
      String_PAN:=PAN;
      String_EWK:=EWK;
      MkIdx:=MkIndex;
      
      Timer3.Enabled:=true;
end;


// эта процедура вызывается только при ошибке работы с картой, при которой возможен повтор
// см. где - Timer4.Enabled:=True
procedure TForm1.Timer4Timer(Sender: TObject);
begin
  Timer4.Enabled:=false;
  Inc(RetryCount);
  TransResult:='Повтор чтения карты';
  ResultLabel.Caption:=TransResult;
  Application.ProcessMessages;

  ExecTransaction(TimeRun.Value,UseEvents.Checked);
  SetValueFields;

end;

end.
