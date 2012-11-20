object ExecForm: TExecForm
  Left = 415
  Top = 296
  BorderIcons = []
  BorderStyle = bsSingle
  Caption = ' '#1048#1076#1077#1090' '#1074#1099#1087#1086#1083#1085#1077#1085#1080#1077' '#1090#1088#1072#1085#1079#1072#1082#1094#1080#1080
  ClientHeight = 125
  ClientWidth = 355
  Color = clBtnFace
  Font.Charset = DEFAULT_CHARSET
  Font.Color = clWindowText
  Font.Height = -11
  Font.Name = 'MS Sans Serif'
  Font.Style = []
  FormStyle = fsStayOnTop
  OldCreateOrder = False
  Position = poMainFormCenter
  OnCreate = FormCreate
  OnHide = FormHide
  OnShow = FormShow
  PixelsPerInch = 96
  TextHeight = 13
  object Label1: TLabel
    Left = 24
    Top = 27
    Width = 245
    Height = 13
    Caption = #1055#1086#1078#1072#1083#1091#1081#1089#1090#1072', '#1078#1076#1080#1090#1077' ( '#1086#1089#1090#1072#1083#1086#1089#1100' '#1085#1077' '#1073#1086#1083#1077#1077' '
    Font.Charset = DEFAULT_CHARSET
    Font.Color = clWindowText
    Font.Height = -11
    Font.Name = 'MS Sans Serif'
    Font.Style = [fsBold]
    ParentFont = False
  end
  object TimeLabel: TLabel
    Left = 270
    Top = 27
    Width = 37
    Height = 13
    Caption = '0 '#1089#1077#1082')'
    Font.Charset = DEFAULT_CHARSET
    Font.Color = clWindowText
    Font.Height = -11
    Font.Name = 'MS Sans Serif'
    Font.Style = [fsBold]
    ParentFont = False
  end
  object PB: TProgressBar
    Left = 25
    Top = 50
    Width = 308
    Height = 16
    Smooth = True
    TabOrder = 0
  end
  object BitBtn1: TBitBtn
    Left = 133
    Top = 88
    Width = 97
    Height = 25
    Cancel = True
    Caption = #1054#1090#1084#1077#1085#1080#1090#1100
    ModalResult = 2
    TabOrder = 1
    OnClick = BitBtn1Click
    Glyph.Data = {
      76010000424D7601000000000000760000002800000020000000100000000100
      04000000000000010000130B0000130B00001000000000000000000000000000
      800000800000008080008000000080008000808000007F7F7F00BFBFBF000000
      FF0000FF000000FFFF00FF000000FF00FF00FFFF0000FFFFFF00333333333333
      3333333333FFFFF3333333333999993333333333F77777FFF333333999999999
      33333337777FF377FF3333993370739993333377FF373F377FF3399993000339
      993337777F777F3377F3393999707333993337F77737333337FF993399933333
      399377F3777FF333377F993339903333399377F33737FF33377F993333707333
      399377F333377FF3377F993333101933399377F333777FFF377F993333000993
      399377FF3377737FF7733993330009993933373FF3777377F7F3399933000399
      99333773FF777F777733339993707339933333773FF7FFF77333333999999999
      3333333777333777333333333999993333333333377777333333}
    NumGlyphs = 2
  end
  object Timer1: TTimer
    Enabled = False
    OnTimer = Timer1Timer
    Left = 320
    Top = 8
  end
end
