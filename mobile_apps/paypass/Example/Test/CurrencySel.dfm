object CurrencySelect: TCurrencySelect
  Left = 392
  Top = 318
  BorderStyle = bsDialog
  Caption = ' '#1042#1099#1073#1077#1088#1080#1090#1077' '#1074#1072#1083#1102#1090#1091' '#1086#1087#1077#1088#1072#1094#1080#1080
  ClientHeight = 206
  ClientWidth = 244
  Color = clBtnFace
  Font.Charset = DEFAULT_CHARSET
  Font.Color = clWindowText
  Font.Height = -11
  Font.Name = 'MS Sans Serif'
  Font.Style = []
  OldCreateOrder = False
  Position = poMainFormCenter
  PixelsPerInch = 96
  TextHeight = 13
  object RG: TRadioGroup
    Left = 0
    Top = 12
    Width = 244
    Height = 137
    Align = alTop
    Caption = '  '#1042#1072#1083#1102#1090#1099'  '
    Font.Charset = DEFAULT_CHARSET
    Font.Color = clWindowText
    Font.Height = -11
    Font.Name = 'MS Sans Serif'
    Font.Style = [fsBold]
    ItemIndex = 0
    Items.Strings = (
      #1056#1091#1073#1083#1100'               ( 643 )'
      #1044#1086#1083#1083#1072#1088'            ( 840 )'
      #1045#1074#1088#1086'                ( 978 )')
    ParentFont = False
    TabOrder = 0
  end
  object Button1: TButton
    Left = 27
    Top = 164
    Width = 75
    Height = 25
    Caption = 'OK'
    Default = True
    ModalResult = 1
    TabOrder = 1
  end
  object Button2: TButton
    Left = 140
    Top = 164
    Width = 75
    Height = 25
    Cancel = True
    Caption = #1054#1090#1084#1077#1085#1072
    ModalResult = 2
    TabOrder = 2
  end
  object Panel1: TPanel
    Left = 0
    Top = 0
    Width = 244
    Height = 12
    Align = alTop
    BevelOuter = bvNone
    TabOrder = 3
  end
end
