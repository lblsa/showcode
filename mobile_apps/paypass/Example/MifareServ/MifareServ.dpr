program Loader;

{$APPTYPE CONSOLE}

uses
  Classes,SysUtils,System,Windows,Messages,Controls,Forms,IniFiles,StrUtils,
  OverbyteIcsWSocket,WSocketS,WSocketTS;

type
  TCommunication = class(TObject)
   private

    FWSocketServer: TWSocketThrdServer;

// parameters from ini-file
    TCPPort:String;                     // default - 1402

   public


     procedure ReadSettings(const inifilename:String);
     function  StartWaitForConnect:boolean;
     procedure ProcessMessages;

     procedure WSocketServerClientConnect(Sender: TObject; Client: TWSocketClient; Error: Word);
     procedure WSocketServerClientDisconnect(Sender: TObject; Client: TWSocketClient; Error: Word);


     procedure ClientDataAvailable(Sender : TObject; Error  : Word);
     procedure ClientBgException( Sender: TObject; E: Exception; var CanClose : Boolean);
     procedure ClientSessionClose(Sender : TObject; Error  : Word);


end;



var
  comms:TCommunication;



procedure TCommunication.ReadSettings(const inifilename:String);
var s:String;  inifile:TMemIniFile;
begin
   inifile:=TMemIniFile.Create(inifilename);

  try

  inifile.CaseSensitive:=False;
  s:=inifile.ReadString('Main Settings','TCP Port','1402');
  s:=Trim(s);
  if Length(s)>5 then
    SetLength(s,5);

  TCPPort:=s;

 finally
  inifile.Free;
 end;

end;

function  TCommunication.StartWaitForConnect:boolean;
begin
   Result:=false;


    Writeln('');
    Writeln('Start program: '+LocalIPList.Strings[0]+','+TCPPort);
    Writeln('');

 try
     if FWSocketServer=nil then FWSocketServer:=TWSocketThrdServer.Create(nil);
     FWSocketServer.Banner:='';
     FWSocketServer.Addr := '0.0.0.0';
     FWSocketServer.Proto := 'tcp';
     FWSocketServer.Port  := TCPPort;
     FWSocketServer.OnClientConnect:=WSocketServerClientConnect;
     FWSocketServer.OnClientDisconnect:=WSocketServerClientDisconnect;
     FWSocketServer.ComponentOptions:=[wsoNoReceiveLoop,wsoTcpNoDelay];
     FWSocketServer.Listen;
     Result:=true;

 except
   Writeln('');
   Writeln('Error opening TCP port: '+TCPPort);
   Writeln('');
 end;

end;

procedure TCommunication.WSocketServerClientConnect(Sender: TObject; Client: TWSocketClient; Error: Word);
begin
 if Error = 0 then
   begin
     Writeln('Client connected. Remote: ' + Client.PeerAddr + ',' + Client.PeerPort);

      Client.LineMode:=false;
      Client.OnDataAvailable     := ClientDataAvailable;
      Client.OnBgException       := ClientBgException;
      Client.OnSessionClosed     :=ClientSessionClose;

   end;

end;

procedure TCommunication.WSocketServerClientDisconnect(Sender : TObject;Client: TWSocketClient; Error  : Word);
begin
        WriteLn('Client disconnecting: ' + Client.PeerAddr);
end;




procedure TCommunication.ClientSessionClose(Sender : TObject; Error  : Word);
begin
           with Sender as TWSocketThrdClient do
              begin
                Free;
              end;
end;


procedure TCommunication.ClientBgException( Sender: TObject; E: Exception; var CanClose : Boolean);
begin
    WriteLn('TCP Client exception occured: ' + E.ClassName + ': ' + E.Message);
    CanClose := TRUE;   { Goodbye client ! }
end;


procedure  TCommunication.ProcessMessages;
begin
    if Assigned(FWSocketServer) then
       FWSocketServer.MessageLoop;
    Application.ProcessMessages;
end;




function ParseRequest_MakeResponse(ReqData:String):string;
var CmdName:char;
    datalen,i:Integer;
    s:string;
    buf:array[0..127] of char;
    buf2:array[0..127] of char;

begin
Result:='';

datalen:=Length(ReqData)-1;
if datalen <=0 then exit;

CmdName:=ReqData[1]; // first byte - Command Name



case CmdName of
'I':  begin
       WriteLn(' ');
       WriteLn('"Initialize" Command');
       if Length(ReqData)>2 then
         begin

          if ReqData[2] = Char($3) then
               WriteLn('It is Mifare Standart Card')
           else
          if ReqData[2] = Char($4) then
               WriteLn('It is Mifare Ultralight Card')
           else
               WriteLn('Unknown Card Type');

        if Length(ReqData)>3 then
          begin
          s:=Copy(ReqData,3,Length(ReqData)-2);
          FillChar(buf,$0,sizeof(buf));
          StrPCopy(buf2,s);
          BinToHex(buf2, buf, Length(s));
          WriteLn('Card UID:'+StrPas(buf))
          end;

       WriteLn(' ');
       
         end;

       Result:='P';
       WriteLn('Send "Get PCD Parameters" command');
      end;

'A': WriteLn('Response on "Autenticate block" Command');

'R': begin
     WriteLn('Response on "Read blocks" Command: '+IntToStr(datalen)+ ' bytes');
     if datalen>2 then
          begin
           s:=Copy(ReqData,2,datalen-1);
           FillChar(buf,$0,sizeof(buf));
//           StrPCopy(buf2,s);
//           BinToHex(buf2, buf, Length(s));
//           WriteLn( StrPas(buf));
           if Length(s)>54 then
            begin
             i:=Integer(s[54]);
             WriteLn('Num metro trips:'+IntToStr(i));
            end;
         end;
     Result:='F';
     WriteLn('Send Finish command');
     WriteLn('');
     end;

'W': WriteLn('Response on "Write blocks" Command');

'P': begin
      WriteLn('Response on "Get PCD Parameters" Command: '+IntToStr(datalen)+ ' bytes');
      Result:='R'+Char($44)+Char($0); // make command for read blocks
      WriteLn('Send "Read Blocks" command');
     end ;

'E': WriteLn('Response on "PCD Exchange" Command');

end;


end;


procedure TCommunication.ClientDataAvailable(Sender : TObject; Error  : Word);
var
    Count,MsgSize : Integer;
    s1,s2:String;
    len:Byte;
    buf :array[0..255] of char;
    p:PChar;

begin

       with Sender as TWSocketThrdClient do
        begin


//RcvdCount return the number of characters received in the buffer but not read yet.
// Do not confuse with ReadCount which returns the number of chars  already received.


           FillChar(buf,sizeof(buf),0);
           Count:=Receive(@buf, sizeof(buf));
           if Count<=0 then exit;
           
// в начале сообщения летит его длина - 1 байт
           MsgSize:=Integer(buf[0]);
           if Count<>(MsgSize+1) then
             WriteLn('Received more bytes '+IntToStr(Count-(MsgSize+1)));


 // устраняем первый байта (длина сообщения)
         p:=@buf[1];
         SetString(s1, p,(Count-1));

         s2:=ParseRequest_MakeResponse(s1);

        if Length(s2)>0 then
         begin
             with Sender as TWSocketThrdClient do
              begin
// need add 1 bytes (packet size) at begin data
               len:=Length(s2);
               p:=@len;
               Insert(p^,s2,1);
               SendStr(s2);
              end;
          end;

         end;
end;


//////////////////////////////////////////////////////////////////////////////////
//  function  main
/////////////////////////////////////////////////////////////////////////////////
begin
 { TODO -oUser -cConsole Main : Insert code here }

try
  comms:=TCommunication.Create;
  try
  
  comms.ReadSettings('mifare.ini'); // read settings for communication channels
  if  not comms.StartWaitForConnect then
    begin
      Writeln('');
      Writeln('Error: can not start communications!');
      Writeln('');
      Writeln('Press <Enter> to exit');
      Readln;
      exit;
    end;


   Writeln('');
   Writeln('Waiting for connections ...');
   Writeln('');



   while true do
      comms.ProcessMessages;


  finally
    if Assigned(comms) then
       comms.Free;
  end;

    except
 //Handle error condition
   on E: Exception do
    begin
    WriteLn('Program terminated by exception:');
    WriteLn(E.Message);
    Writeln('');
    WriteLn('Press <Enter> to exit');
    ReadLn;
    //Set ExitCode <> 0 to flag error condition (by convention)
    ExitCode := (-1);
    end;
  end;


end.
