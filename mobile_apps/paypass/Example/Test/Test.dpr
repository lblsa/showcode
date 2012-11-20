program Test;

uses
  Forms,
  Emul in 'Emul.pas' {Form1},
  POSEntrySel in 'POSEntrySel.pas' {POSEntrySelect},
  RunForm in 'RunForm.pas' {ExecForm},
  CurrencySel in 'CurrencySel.pas' {CurrencySelect},
  LanCOM_TLB in 'D:\Program Files\Borland\Delphi7\Imports\LanCOM_TLB.pas';

{$R *.res}

begin
  Application.Initialize;
  Application.CreateForm(TForm1, Form1);
  Application.CreateForm(TPOSEntrySelect, POSEntrySelect);
  Application.CreateForm(TExecForm, ExecForm);
  Application.CreateForm(TCurrencySelect, CurrencySelect);
  Application.Run;
end.
