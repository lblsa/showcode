unit RunForm;

interface

uses
  Windows, Messages, SysUtils, Variants, Classes, Graphics, Controls, Forms,
  Dialogs, StdCtrls, Buttons, ComCtrls, ExtCtrls;

type
  TExecForm = class(TForm)
    Timer1: TTimer;
    PB: TProgressBar;
    BitBtn1: TBitBtn;
    Label1: TLabel;
    TimeLabel: TLabel;
    procedure Timer1Timer(Sender: TObject);
    procedure FormCreate(Sender: TObject);
    procedure BitBtn1Click(Sender: TObject);
    procedure FormShow(Sender: TObject);
    procedure FormHide(Sender: TObject);
  private
    { Private declarations }
  public
    { Public declarations }
     FullTime:Integer;
  end;

var
  ExecForm: TExecForm;

implementation

{$R *.dfm}

procedure TExecForm.Timer1Timer(Sender: TObject);
begin
Timer1.Enabled:=false;
FullTime:=FullTime-1;
if FullTime<=0 then
begin
 Hide;
 exit;
end;

TimeLabel.Caption:=IntToStr(FullTime)+' сек)';
PB.Position:=PB.Position+1;
Timer1.Enabled:=true;
end;

procedure TExecForm.FormCreate(Sender: TObject);
begin
FullTime:=0;
end;

procedure TExecForm.BitBtn1Click(Sender: TObject);
begin
Timer1.Enabled:=false;
ModalResult:=mrCancel;
end;

procedure TExecForm.FormShow(Sender: TObject);
begin
TimeLabel.Caption:=IntToStr(FullTime)+' сек)';
PB.Max:=FullTime;
PB.Position:=0;
Timer1.Enabled:=true;
ModalResult:=mrNone;
end;

procedure TExecForm.FormHide(Sender: TObject);
begin
Timer1.Enabled:=false;
FullTime:=FullTime-1;
PB.Position:=0;
end;

end.
