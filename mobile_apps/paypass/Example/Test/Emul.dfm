object Form1: TForm1
  Left = 110
  Top = 133
  Width = 1070
  Height = 782
  BorderIcons = [biSystemMenu]
  Caption = '   LanTest'
  Color = clBtnFace
  Font.Charset = DEFAULT_CHARSET
  Font.Color = clWindowText
  Font.Height = -11
  Font.Name = 'MS Sans Serif'
  Font.Style = []
  OldCreateOrder = False
  Position = poScreenCenter
  OnCreate = FormCreate
  OnDestroy = FormDestroy
  PixelsPerInch = 96
  TextHeight = 13
  object Splitter1: TSplitter
    Left = 239
    Top = 0
    Height = 690
    ResizeStyle = rsLine
  end
  object Panel1: TPanel
    Left = 242
    Top = 0
    Width = 820
    Height = 690
    Align = alClient
    BevelOuter = bvNone
    TabOrder = 0
    object TransGrid: TXStringGrid
      Left = 0
      Top = 0
      Width = 820
      Height = 690
      Align = alClient
      DefaultRowHeight = 30
      FixedCols = 2
      RowCount = 30
      Options = [goFixedVertLine, goFixedHorzLine, goVertLine, goHorzLine, goRowSizing, goColSizing, goEditing]
      TabOrder = 0
      OnMouseDown = TransGridMouseDown
      OnSelectCell = TransGridSelectCell
      FixedLineColor = clBlack
      Columns = <
        item
          HeaderFont.Charset = DEFAULT_CHARSET
          HeaderFont.Color = clWindowText
          HeaderFont.Height = -11
          HeaderFont.Name = 'MS Sans Serif'
          HeaderFont.Style = []
          HeaderAlignment = taCenter
          Caption = ' ID  '#1087#1086#1083#1103
          Color = clMenu
          Width = 40
          Font.Charset = DEFAULT_CHARSET
          Font.Color = clWindowText
          Font.Height = -11
          Font.Name = 'MS Sans Serif'
          Font.Style = [fsBold]
          Alignment = taCenter
          EditorInheritsCellProps = False
        end
        item
          HeaderFont.Charset = DEFAULT_CHARSET
          HeaderFont.Color = clWindowText
          HeaderFont.Height = -11
          HeaderFont.Name = 'MS Sans Serif'
          HeaderFont.Style = []
          HeaderAlignment = taCenter
          Caption = #1054#1087#1080#1089#1072#1085#1080#1077'   '#1087#1086#1083#1103
          Width = 222
          Font.Charset = DEFAULT_CHARSET
          Font.Color = clWindowText
          Font.Height = -11
          Font.Name = 'MS Sans Serif'
          Font.Style = []
          EditorInheritsCellProps = False
        end
        item
          HeaderFont.Charset = DEFAULT_CHARSET
          HeaderFont.Color = clWindowText
          HeaderFont.Height = -11
          HeaderFont.Name = 'MS Sans Serif'
          HeaderFont.Style = []
          HeaderAlignment = taCenter
          Caption = #1048#1084#1103' '#1087#1086#1083#1103
          Width = 136
          Font.Charset = DEFAULT_CHARSET
          Font.Color = clWindowText
          Font.Height = -11
          Font.Name = 'MS Sans Serif'
          Font.Style = []
          Alignment = taCenter
          EditorInheritsCellProps = False
        end
        item
          HeaderFont.Charset = DEFAULT_CHARSET
          HeaderFont.Color = clWindowText
          HeaderFont.Height = -11
          HeaderFont.Name = 'MS Sans Serif'
          HeaderFont.Style = []
          HeaderAlignment = taCenter
          Caption = #1047#1072#1087#1088#1086#1089
          Color = clInfoBk
          Width = 166
          Font.Charset = DEFAULT_CHARSET
          Font.Color = clBlack
          Font.Height = -11
          Font.Name = 'MS Sans Serif'
          Font.Style = [fsBold]
          Alignment = taCenter
          Editor = EditCellEditor1
          EditorInheritsCellProps = False
        end
        item
          HeaderFont.Charset = DEFAULT_CHARSET
          HeaderFont.Color = clWindowText
          HeaderFont.Height = -11
          HeaderFont.Name = 'MS Sans Serif'
          HeaderFont.Style = []
          HeaderAlignment = taCenter
          Caption = #1054#1090#1074#1077#1090
          Width = 230
          Font.Charset = DEFAULT_CHARSET
          Font.Color = clMaroon
          Font.Height = -11
          Font.Name = 'MS Sans Serif'
          Font.Style = [fsBold]
          Alignment = taCenter
          EditorInheritsCellProps = False
        end>
      MultiLine = True
      ImmediateEditMode = True
      ColWidths = (
        40
        222
        136
        166
        230)
    end
  end
  object Panel2: TPanel
    Left = 0
    Top = 0
    Width = 239
    Height = 690
    Align = alLeft
    BevelOuter = bvNone
    TabOrder = 1
    object OpsGrid: TStringGrid
      Left = 0
      Top = 0
      Width = 239
      Height = 584
      Align = alClient
      ColCount = 2
      RowCount = 15
      Font.Charset = DEFAULT_CHARSET
      Font.Color = clWindowText
      Font.Height = -11
      Font.Name = 'MS Sans Serif'
      Font.Style = [fsBold]
      Options = [goFixedVertLine, goFixedHorzLine, goVertLine, goHorzLine, goRangeSelect, goRowSelect]
      ParentFont = False
      ScrollBars = ssVertical
      TabOrder = 0
      OnSelectCell = OpsGridSelectCell
      ColWidths = (
        40
        205)
    end
    object GroupBox1: TGroupBox
      Left = 0
      Top = 593
      Width = 239
      Height = 97
      Align = alBottom
      Caption = ' '#1055#1072#1088#1072#1084#1077#1090#1099' '#1089#1086#1077#1076#1080#1085#1077#1085#1080#1103' '#1089' '#1089#1077#1088#1074#1077#1088#1086#1084' '
      Font.Charset = DEFAULT_CHARSET
      Font.Color = clWindowText
      Font.Height = -11
      Font.Name = 'MS Sans Serif'
      Font.Style = [fsBold]
      ParentFont = False
      TabOrder = 1
      object Label1: TLabel
        Left = 13
        Top = 30
        Width = 86
        Height = 13
        Caption = 'TCP/IP '#1072#1076#1088#1077#1089':'
      end
      object Label2: TLabel
        Left = 13
        Top = 65
        Width = 34
        Height = 13
        Caption = #1055#1086#1088#1090':'
      end
      object PostIp: TEdit
        Left = 119
        Top = 27
        Width = 106
        Height = 21
        MaxLength = 15
        TabOrder = 0
        Text = '127.0.0.1'
      end
      object PostPort: TEdit
        Left = 120
        Top = 60
        Width = 47
        Height = 21
        MaxLength = 5
        TabOrder = 1
        Text = '2000'
      end
    end
    object Panel4: TPanel
      Left = 0
      Top = 584
      Width = 239
      Height = 9
      Align = alBottom
      BevelOuter = bvNone
      TabOrder = 2
    end
  end
  object Panel3: TPanel
    Left = 0
    Top = 690
    Width = 1062
    Height = 65
    Align = alBottom
    BevelInner = bvLowered
    TabOrder = 2
    DesignSize = (
      1062
      65)
    object BtnExec: TBitBtn
      Left = 724
      Top = 20
      Width = 120
      Height = 27
      Anchors = [akRight, akBottom]
      Caption = #1042#1099#1087#1086#1083#1085#1080#1090#1100
      Font.Charset = DEFAULT_CHARSET
      Font.Color = clWindowText
      Font.Height = -11
      Font.Name = 'MS Sans Serif'
      Font.Style = [fsBold]
      ParentFont = False
      TabOrder = 0
      OnClick = BtnExecClick
      Glyph.Data = {
        76010000424D7601000000000000760000002800000020000000100000000100
        04000000000000010000120B0000120B00001000000000000000000000000000
        800000800000008080008000000080008000808000007F7F7F00BFBFBF000000
        FF0000FF000000FFFF00FF000000FF00FF00FFFF0000FFFFFF00555555555555
        555555555555555555555555555555555555555555FF55555555555559055555
        55555555577FF5555555555599905555555555557777F5555555555599905555
        555555557777FF5555555559999905555555555777777F555555559999990555
        5555557777777FF5555557990599905555555777757777F55555790555599055
        55557775555777FF5555555555599905555555555557777F5555555555559905
        555555555555777FF5555555555559905555555555555777FF55555555555579
        05555555555555777FF5555555555557905555555555555777FF555555555555
        5990555555555555577755555555555555555555555555555555}
      NumGlyphs = 2
    end
    object Panel5: TPanel
      Left = 2
      Top = 2
      Width = 564
      Height = 61
      Align = alLeft
      BevelInner = bvLowered
      TabOrder = 1
      object Label3: TLabel
        Left = 246
        Top = 11
        Width = 190
        Height = 13
        Caption = #1052#1072#1082#1089'. '#1074#1088#1077#1084#1103' '#1074#1099#1087#1086#1083#1085#1077#1085#1080#1103' ('#1089#1077#1082'):'
        Font.Charset = DEFAULT_CHARSET
        Font.Color = clWindowText
        Font.Height = -11
        Font.Name = 'MS Sans Serif'
        Font.Style = [fsBold]
        ParentFont = False
      end
      object GroupBox2: TGroupBox
        Left = 2
        Top = 2
        Width = 237
        Height = 57
        Align = alLeft
        Caption = ' '#1056#1077#1079#1091#1083#1100#1090#1072#1090': '
        Font.Charset = DEFAULT_CHARSET
        Font.Color = clWindowText
        Font.Height = -11
        Font.Name = 'MS Sans Serif'
        Font.Style = [fsBold]
        ParentFont = False
        TabOrder = 0
        object ResultLabel: TLabel
          Left = 2
          Top = 24
          Width = 233
          Height = 31
          Align = alBottom
          Alignment = taCenter
          AutoSize = False
          Caption = #1054#1090#1089#1091#1090#1089#1090#1074#1091#1077#1090
          Font.Charset = DEFAULT_CHARSET
          Font.Color = clRed
          Font.Height = -11
          Font.Name = 'MS Sans Serif'
          Font.Style = [fsBold]
          ParentFont = False
        end
      end
      object TimeRun: TSpinEdit
        Left = 448
        Top = 9
        Width = 49
        Height = 22
        Increment = 2
        MaxLength = 3
        MaxValue = 600
        MinValue = 25
        TabOrder = 1
        Value = 60
      end
      object UseEvents: TCheckBox
        Left = 248
        Top = 35
        Width = 308
        Height = 17
        Caption = #1048#1089#1087#1086#1083#1100#1079#1086#1074#1072#1090#1100' '#1089#1086#1073#1099#1090#1080#1103' '#1076#1083#1103' ContactLess'
        Checked = True
        Font.Charset = DEFAULT_CHARSET
        Font.Color = clWindowText
        Font.Height = -11
        Font.Name = 'MS Sans Serif'
        Font.Style = [fsBold]
        ParentFont = False
        State = cbChecked
        TabOrder = 2
      end
    end
  end
  object EditCellEditor1: TEditCellEditor
    DefaultText = '-'
    hasElipsis = True
    OnElipsisClick = EditCellEditor1ElipsisClick
    ElipsisCaption = '...'
    Left = 976
    Top = 448
  end
  object PopMenu: TPopupMenu
    AutoPopup = False
    Left = 978
    Top = 416
    object N1: TMenuItem
      Caption = #1050#1086#1087#1080#1088#1086#1074#1072#1090#1100
      OnClick = N1Click
    end
  end
  object Timer1: TTimer
    Enabled = False
    Interval = 100
    OnTimer = Timer1Timer
    Left = 976
    Top = 361
  end
  object FB: TFolderBrowser
    BrowseFlags = []
    Title = #1042#1099#1073#1077#1088#1080#1090#1077' '#1082#1072#1090#1072#1083#1086#1075':'
    Left = 976
    Top = 385
  end
  object Timer2: TTimer
    Enabled = False
    Interval = 10
    OnTimer = Timer2Timer
    Left = 920
    Top = 648
  end
  object Timer3: TTimer
    Enabled = False
    Interval = 10
    OnTimer = Timer3Timer
    Left = 970
    Top = 648
  end
  object Timer4: TTimer
    Enabled = False
    Interval = 300
    OnTimer = Timer4Timer
    Left = 1010
    Top = 648
  end
end
