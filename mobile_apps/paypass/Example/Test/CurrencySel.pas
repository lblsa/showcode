unit CurrencySel;

interface

uses
  Windows, Messages, SysUtils, Variants, Classes, Graphics, Controls, Forms,
  Dialogs, StdCtrls, ExtCtrls;

type
  TCurrencySelect = class(TForm)
    RG: TRadioGroup;
    Button1: TButton;
    Button2: TButton;
    Panel1: TPanel;
  private
    { Private declarations }
  public
    { Public declarations }
  end;

var
  CurrencySelect: TCurrencySelect;

implementation

{$R *.dfm}

end.
