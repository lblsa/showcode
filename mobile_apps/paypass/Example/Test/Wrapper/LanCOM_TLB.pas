unit LanCOM_TLB;

// ************************************************************************ //
// WARNING                                                                    
// -------                                                                    
// The types declared in this file were generated from data read from a       
// Type Library. If this type library is explicitly or indirectly (via        
// another type library referring to this type library) re-imported, or the   
// 'Refresh' command of the Type Library Editor activated while editing the   
// Type Library, the contents of this file will be regenerated and all        
// manual modifications will be lost.                                         
// ************************************************************************ //

// PASTLWTR : 1.2
// File generated on 29.08.2011 11:01:25 from Type Library described below.

// ************************************************************************  //
// Type Lib: C:\Projects\LanCOM\LanCOM.dll (1)
// LIBID: {F736A91F-517F-4C8E-81ED-A45A91F34B25}
// LCID: 0
// Helpfile: 
// HelpString: LanCOM Library
// DepndLst: 
//   (1) v2.0 stdole, (D:\WINXP\system32\stdole2.tlb)
// ************************************************************************ //
// *************************************************************************//
// NOTE:                                                                      
// Items guarded by $IFDEF_LIVE_SERVER_AT_DESIGN_TIME are used by properties  
// which return objects that may need to be explicitly created via a function 
// call prior to any access via the property. These items have been disabled  
// in order to prevent accidental use from within the object inspector. You   
// may enable them by defining LIVE_SERVER_AT_DESIGN_TIME or by selectively   
// removing them from the $IFDEF blocks. However, such items must still be    
// programmatically created via a method of the appropriate CoClass before    
// they can be used.                                                          
{$TYPEDADDRESS OFF} // Unit must be compiled without type-checked pointers. 
{$WARN SYMBOL_PLATFORM OFF}
{$WRITEABLECONST ON}
{$VARPROPSETTER ON}
interface

uses Windows, ActiveX, Classes, Graphics, OleServer, StdVCL, Variants;
  

// *********************************************************************//
// GUIDS declared in the TypeLibrary. Following prefixes are used:        
//   Type Libraries     : LIBID_xxxx                                      
//   CoClasses          : CLASS_xxxx                                      
//   DISPInterfaces     : DIID_xxxx                                       
//   Non-DISP interfaces: IID_xxxx                                        
// *********************************************************************//
const
  // TypeLibrary Major and minor versions
  LanCOMMajorVersion = 1;
  LanCOMMinorVersion = 1;

  LIBID_LanCOM: TGUID = '{F736A91F-517F-4C8E-81ED-A45A91F34B25}';

  IID_ITransData: TGUID = '{52D0C76F-CBE0-4A88-B808-B05D4BECC834}';
  CLASS_TransData: TGUID = '{D7F12E9A-14D1-4437-A0FC-0D5C4113C660}';
  DIID_ITransSenderEvents: TGUID = '{55E43BB7-1E1A-4358-9F65-CA5CD866B793}';
  IID_ITransSender: TGUID = '{9E13876D-EDE8-4B1B-BE89-B8497B693C6B}';
  CLASS_TransSender: TGUID = '{8B6FC1F7-4737-474D-9324-9C7B7CF31EB3}';
type

// *********************************************************************//
// Forward declaration of types defined in TypeLibrary                    
// *********************************************************************//
  ITransData = interface;
  ITransDataDisp = dispinterface;
  ITransSenderEvents = dispinterface;
  ITransSender = interface;
  ITransSenderDisp = dispinterface;

// *********************************************************************//
// Declaration of CoClasses defined in Type Library                       
// (NOTE: Here we map each CoClass to its Default Interface)              
// *********************************************************************//
  TransData = ITransData;
  TransSender = ITransSender;


// *********************************************************************//
// Declaration of structures, unions and aliases.                         
// *********************************************************************//
  PPUserType1 = ^ITransData; {*}


// *********************************************************************//
// Interface: ITransData
// Flags:     (4416) Dual OleAutomation Dispatchable
// GUID:      {52D0C76F-CBE0-4A88-B808-B05D4BECC834}
// *********************************************************************//
  ITransData = interface(IDispatch)
    ['{52D0C76F-CBE0-4A88-B808-B05D4BECC834}']
    function Get_Amount: WideString; safecall;
    procedure Set_Amount(const Value: WideString); safecall;
    function Get_CurrencyCode: WideString; safecall;
    procedure Set_CurrencyCode(const Value: WideString); safecall;
    function Get_DateTimeHost: WideString; safecall;
    procedure Set_DateTimeHost(const Value: WideString); safecall;
    function Get_CardEntryMode: Integer; safecall;
    procedure Set_CardEntryMode(Value: Integer); safecall;
    function Get_PAN: WideString; safecall;
    procedure Set_PAN(const Value: WideString); safecall;
    function Get_CardExpiryDate: WideString; safecall;
    procedure Set_CardExpiryDate(const Value: WideString); safecall;
    function Get_TRACK2: WideString; safecall;
    procedure Set_TRACK2(const Value: WideString); safecall;
    function Get_AuthorizationCode: WideString; safecall;
    procedure Set_AuthorizationCode(const Value: WideString); safecall;
    function Get_ReferenceNumber: WideString; safecall;
    procedure Set_ReferenceNumber(const Value: WideString); safecall;
    function Get_ResponseCodeHost: WideString; safecall;
    procedure Set_ResponseCodeHost(const Value: WideString); safecall;
    function Get_TextResponse: WideString; safecall;
    procedure Set_TextResponse(const Value: WideString); safecall;
    function Get_DateTimeCRM: WideString; safecall;
    procedure Set_DateTimeCRM(const Value: WideString); safecall;
    function Get_TrxID: Integer; safecall;
    procedure Set_TrxID(Value: Integer); safecall;
    function Get_OrigOperation: Integer; safecall;
    procedure Set_OrigOperation(Value: Integer); safecall;
    function Get_OperationCode: Integer; safecall;
    procedure Set_OperationCode(Value: Integer); safecall;
    function Get_TrxIDCRM: Integer; safecall;
    procedure Set_TrxIDCRM(Value: Integer); safecall;
    function Get_CRMID: WideString; safecall;
    procedure Set_CRMID(const Value: WideString); safecall;
    function Get_Status: Integer; safecall;
    procedure Set_Status(Value: Integer); safecall;
    function Get_TrxIDHost: Integer; safecall;
    procedure Set_TrxIDHost(Value: Integer); safecall;
    function Get_CommodityCode: WideString; safecall;
    procedure Set_CommodityCode(const Value: WideString); safecall;
    function Get_PaymentDetails: WideString; safecall;
    procedure Set_PaymentDetails(const Value: WideString); safecall;
    function Get_ProviderCode: WideString; safecall;
    procedure Set_ProviderCode(const Value: WideString); safecall;
    function Get_ProcessingFlag: Integer; safecall;
    procedure Set_ProcessingFlag(Value: Integer); safecall;
    function Get_FileNameResult: WideString; safecall;
    procedure Set_FileNameResult(const Value: WideString); safecall;
    function Get_FileNameReport: WideString; safecall;
    procedure Set_FileNameReport(const Value: WideString); safecall;
    function Get_FileName: WideString; safecall;
    procedure Set_FileName(const Value: WideString); safecall;
    function Get_PathParameters: WideString; safecall;
    procedure Set_PathParameters(const Value: WideString); safecall;
    function Get_CVV2: WideString; safecall;
    procedure Set_CVV2(const Value: WideString); safecall;
    function Get_AmountFee: WideString; safecall;
    procedure Set_AmountFee(const Value: WideString); safecall;
    function Get_TerminalOutID: WideString; safecall;
    procedure Set_TerminalOutID(const Value: WideString); safecall;
    function Get_ReceiptData: WideString; safecall;
    procedure Set_ReceiptData(const Value: WideString); safecall;
    function Get_EncPinBlock: WideString; safecall;
    procedure Set_EncPinBlock(const Value: WideString); safecall;
    property Amount: WideString read Get_Amount write Set_Amount;
    property CurrencyCode: WideString read Get_CurrencyCode write Set_CurrencyCode;
    property DateTimeHost: WideString read Get_DateTimeHost write Set_DateTimeHost;
    property CardEntryMode: Integer read Get_CardEntryMode write Set_CardEntryMode;
    property PAN: WideString read Get_PAN write Set_PAN;
    property CardExpiryDate: WideString read Get_CardExpiryDate write Set_CardExpiryDate;
    property TRACK2: WideString read Get_TRACK2 write Set_TRACK2;
    property AuthorizationCode: WideString read Get_AuthorizationCode write Set_AuthorizationCode;
    property ReferenceNumber: WideString read Get_ReferenceNumber write Set_ReferenceNumber;
    property ResponseCodeHost: WideString read Get_ResponseCodeHost write Set_ResponseCodeHost;
    property TextResponse: WideString read Get_TextResponse write Set_TextResponse;
    property DateTimeCRM: WideString read Get_DateTimeCRM write Set_DateTimeCRM;
    property TrxID: Integer read Get_TrxID write Set_TrxID;
    property OrigOperation: Integer read Get_OrigOperation write Set_OrigOperation;
    property OperationCode: Integer read Get_OperationCode write Set_OperationCode;
    property TrxIDCRM: Integer read Get_TrxIDCRM write Set_TrxIDCRM;
    property CRMID: WideString read Get_CRMID write Set_CRMID;
    property Status: Integer read Get_Status write Set_Status;
    property TrxIDHost: Integer read Get_TrxIDHost write Set_TrxIDHost;
    property CommodityCode: WideString read Get_CommodityCode write Set_CommodityCode;
    property PaymentDetails: WideString read Get_PaymentDetails write Set_PaymentDetails;
    property ProviderCode: WideString read Get_ProviderCode write Set_ProviderCode;
    property ProcessingFlag: Integer read Get_ProcessingFlag write Set_ProcessingFlag;
    property FileNameResult: WideString read Get_FileNameResult write Set_FileNameResult;
    property FileNameReport: WideString read Get_FileNameReport write Set_FileNameReport;
    property FileName: WideString read Get_FileName write Set_FileName;
    property PathParameters: WideString read Get_PathParameters write Set_PathParameters;
    property CVV2: WideString read Get_CVV2 write Set_CVV2;
    property AmountFee: WideString read Get_AmountFee write Set_AmountFee;
    property TerminalOutID: WideString read Get_TerminalOutID write Set_TerminalOutID;
    property ReceiptData: WideString read Get_ReceiptData write Set_ReceiptData;
    property EncPinBlock: WideString read Get_EncPinBlock write Set_EncPinBlock;
  end;

// *********************************************************************//
// DispIntf:  ITransDataDisp
// Flags:     (4416) Dual OleAutomation Dispatchable
// GUID:      {52D0C76F-CBE0-4A88-B808-B05D4BECC834}
// *********************************************************************//
  ITransDataDisp = dispinterface
    ['{52D0C76F-CBE0-4A88-B808-B05D4BECC834}']
    property Amount: WideString dispid 1;
    property CurrencyCode: WideString dispid 2;
    property DateTimeHost: WideString dispid 3;
    property CardEntryMode: Integer dispid 4;
    property PAN: WideString dispid 5;
    property CardExpiryDate: WideString dispid 6;
    property TRACK2: WideString dispid 7;
    property AuthorizationCode: WideString dispid 8;
    property ReferenceNumber: WideString dispid 9;
    property ResponseCodeHost: WideString dispid 10;
    property TextResponse: WideString dispid 11;
    property DateTimeCRM: WideString dispid 12;
    property TrxID: Integer dispid 13;
    property OrigOperation: Integer dispid 14;
    property OperationCode: Integer dispid 15;
    property TrxIDCRM: Integer dispid 16;
    property CRMID: WideString dispid 17;
    property Status: Integer dispid 18;
    property TrxIDHost: Integer dispid 19;
    property CommodityCode: WideString dispid 20;
    property PaymentDetails: WideString dispid 21;
    property ProviderCode: WideString dispid 22;
    property ProcessingFlag: Integer dispid 23;
    property FileNameResult: WideString dispid 24;
    property FileNameReport: WideString dispid 25;
    property FileName: WideString dispid 26;
    property PathParameters: WideString dispid 28;
    property CVV2: WideString dispid 29;
    property AmountFee: WideString dispid 30;
    property TerminalOutID: WideString dispid 31;
    property ReceiptData: WideString dispid 201;
    property EncPinBlock: WideString dispid 202;
  end;

// *********************************************************************//
// DispIntf:  ITransSenderEvents
// Flags:     (4096) Dispatchable
// GUID:      {55E43BB7-1E1A-4358-9F65-CA5CD866B793}
// *********************************************************************//
  ITransSenderEvents = dispinterface
    ['{55E43BB7-1E1A-4358-9F65-CA5CD866B793}']
    procedure OnExchange(Result: Integer); dispid 1;
    procedure OnPINExchange(const PAN: WideString; const EWK: WideString; MkIndex: Integer); dispid 2;
  end;

// *********************************************************************//
// Interface: ITransSender
// Flags:     (4416) Dual OleAutomation Dispatchable
// GUID:      {9E13876D-EDE8-4B1B-BE89-B8497B693C6B}
// *********************************************************************//
  ITransSender = interface(IDispatch)
    ['{9E13876D-EDE8-4B1B-BE89-B8497B693C6B}']
    function InitResources: Integer; safecall;
    procedure FreeResources; safecall;
    function SetChannelParam(const IPAddress: WideString; IPPort: Integer; 
                             const X25Script: WideString): Integer; safecall;
    function SetProtocolParam(TimeoutACK: Integer; TimeoutPacket: Integer; CountNAK: Integer; 
                              PacketSize: Integer): Integer; safecall;
    function Exchange(var Request: ITransData; var Response: ITransData; Timeout: Integer): Integer; safecall;
    function Get_ErrorDescription: WideString; safecall;
    function Get_ErrorCode: Integer; safecall;
    function SetControlServerParam(const IPAddress: WideString; IPPort: Integer; Timeout: Integer): Integer; safecall;
    function ControlServerStart: Integer; safecall;
    function ControlServerStop: Integer; safecall;
    function CancelExchange: Integer; safecall;
    property ErrorDescription: WideString read Get_ErrorDescription;
    property ErrorCode: Integer read Get_ErrorCode;
  end;

// *********************************************************************//
// DispIntf:  ITransSenderDisp
// Flags:     (4416) Dual OleAutomation Dispatchable
// GUID:      {9E13876D-EDE8-4B1B-BE89-B8497B693C6B}
// *********************************************************************//
  ITransSenderDisp = dispinterface
    ['{9E13876D-EDE8-4B1B-BE89-B8497B693C6B}']
    function InitResources: Integer; dispid 1;
    procedure FreeResources; dispid 2;
    function SetChannelParam(const IPAddress: WideString; IPPort: Integer; 
                             const X25Script: WideString): Integer; dispid 3;
    function SetProtocolParam(TimeoutACK: Integer; TimeoutPacket: Integer; CountNAK: Integer; 
                              PacketSize: Integer): Integer; dispid 4;
    function Exchange(var Request: ITransData; var Response: ITransData; Timeout: Integer): Integer; dispid 5;
    property ErrorDescription: WideString readonly dispid 6;
    property ErrorCode: Integer readonly dispid 7;
    function SetControlServerParam(const IPAddress: WideString; IPPort: Integer; Timeout: Integer): Integer; dispid 8;
    function ControlServerStart: Integer; dispid 9;
    function ControlServerStop: Integer; dispid 10;
    function CancelExchange: Integer; dispid 11;
  end;

// *********************************************************************//
// The Class CoTransData provides a Create and CreateRemote method to          
// create instances of the default interface ITransData exposed by              
// the CoClass TransData. The functions are intended to be used by             
// clients wishing to automate the CoClass objects exposed by the         
// server of this typelibrary.                                            
// *********************************************************************//
  CoTransData = class
    class function Create: ITransData;
    class function CreateRemote(const MachineName: string): ITransData;
  end;


// *********************************************************************//
// OLE Server Proxy class declaration
// Server Object    : TTransData
// Help String      : Transaction Data Object
// Default Interface: ITransData
// Def. Intf. DISP? : No
// Event   Interface: 
// TypeFlags        : (2) CanCreate
// *********************************************************************//
{$IFDEF LIVE_SERVER_AT_DESIGN_TIME}
  TTransDataProperties= class;
{$ENDIF}
  TTransData = class(TOleServer)
  private
    FIntf:        ITransData;
{$IFDEF LIVE_SERVER_AT_DESIGN_TIME}
    FProps:       TTransDataProperties;
    function      GetServerProperties: TTransDataProperties;
{$ENDIF}
    function      GetDefaultInterface: ITransData;
  protected
    procedure InitServerData; override;
    function Get_Amount: WideString;
    procedure Set_Amount(const Value: WideString);
    function Get_CurrencyCode: WideString;
    procedure Set_CurrencyCode(const Value: WideString);
    function Get_DateTimeHost: WideString;
    procedure Set_DateTimeHost(const Value: WideString);
    function Get_CardEntryMode: Integer;
    procedure Set_CardEntryMode(Value: Integer);
    function Get_PAN: WideString;
    procedure Set_PAN(const Value: WideString);
    function Get_CardExpiryDate: WideString;
    procedure Set_CardExpiryDate(const Value: WideString);
    function Get_TRACK2: WideString;
    procedure Set_TRACK2(const Value: WideString);
    function Get_AuthorizationCode: WideString;
    procedure Set_AuthorizationCode(const Value: WideString);
    function Get_ReferenceNumber: WideString;
    procedure Set_ReferenceNumber(const Value: WideString);
    function Get_ResponseCodeHost: WideString;
    procedure Set_ResponseCodeHost(const Value: WideString);
    function Get_TextResponse: WideString;
    procedure Set_TextResponse(const Value: WideString);
    function Get_DateTimeCRM: WideString;
    procedure Set_DateTimeCRM(const Value: WideString);
    function Get_TrxID: Integer;
    procedure Set_TrxID(Value: Integer);
    function Get_OrigOperation: Integer;
    procedure Set_OrigOperation(Value: Integer);
    function Get_OperationCode: Integer;
    procedure Set_OperationCode(Value: Integer);
    function Get_TrxIDCRM: Integer;
    procedure Set_TrxIDCRM(Value: Integer);
    function Get_CRMID: WideString;
    procedure Set_CRMID(const Value: WideString);
    function Get_Status: Integer;
    procedure Set_Status(Value: Integer);
    function Get_TrxIDHost: Integer;
    procedure Set_TrxIDHost(Value: Integer);
    function Get_CommodityCode: WideString;
    procedure Set_CommodityCode(const Value: WideString);
    function Get_PaymentDetails: WideString;
    procedure Set_PaymentDetails(const Value: WideString);
    function Get_ProviderCode: WideString;
    procedure Set_ProviderCode(const Value: WideString);
    function Get_ProcessingFlag: Integer;
    procedure Set_ProcessingFlag(Value: Integer);
    function Get_FileNameResult: WideString;
    procedure Set_FileNameResult(const Value: WideString);
    function Get_FileNameReport: WideString;
    procedure Set_FileNameReport(const Value: WideString);
    function Get_FileName: WideString;
    procedure Set_FileName(const Value: WideString);
    function Get_PathParameters: WideString;
    procedure Set_PathParameters(const Value: WideString);
    function Get_CVV2: WideString;
    procedure Set_CVV2(const Value: WideString);
    function Get_AmountFee: WideString;
    procedure Set_AmountFee(const Value: WideString);
    function Get_TerminalOutID: WideString;
    procedure Set_TerminalOutID(const Value: WideString);
    function Get_ReceiptData: WideString;
    procedure Set_ReceiptData(const Value: WideString);
    function Get_EncPinBlock: WideString;
    procedure Set_EncPinBlock(const Value: WideString);
  public
    constructor Create(AOwner: TComponent); override;
    destructor  Destroy; override;
    procedure Connect; override;
    procedure ConnectTo(svrIntf: ITransData);
    procedure Disconnect; override;
    property DefaultInterface: ITransData read GetDefaultInterface;
    property Amount: WideString read Get_Amount write Set_Amount;
    property CurrencyCode: WideString read Get_CurrencyCode write Set_CurrencyCode;
    property DateTimeHost: WideString read Get_DateTimeHost write Set_DateTimeHost;
    property CardEntryMode: Integer read Get_CardEntryMode write Set_CardEntryMode;
    property PAN: WideString read Get_PAN write Set_PAN;
    property CardExpiryDate: WideString read Get_CardExpiryDate write Set_CardExpiryDate;
    property TRACK2: WideString read Get_TRACK2 write Set_TRACK2;
    property AuthorizationCode: WideString read Get_AuthorizationCode write Set_AuthorizationCode;
    property ReferenceNumber: WideString read Get_ReferenceNumber write Set_ReferenceNumber;
    property ResponseCodeHost: WideString read Get_ResponseCodeHost write Set_ResponseCodeHost;
    property TextResponse: WideString read Get_TextResponse write Set_TextResponse;
    property DateTimeCRM: WideString read Get_DateTimeCRM write Set_DateTimeCRM;
    property TrxID: Integer read Get_TrxID write Set_TrxID;
    property OrigOperation: Integer read Get_OrigOperation write Set_OrigOperation;
    property OperationCode: Integer read Get_OperationCode write Set_OperationCode;
    property TrxIDCRM: Integer read Get_TrxIDCRM write Set_TrxIDCRM;
    property CRMID: WideString read Get_CRMID write Set_CRMID;
    property Status: Integer read Get_Status write Set_Status;
    property TrxIDHost: Integer read Get_TrxIDHost write Set_TrxIDHost;
    property CommodityCode: WideString read Get_CommodityCode write Set_CommodityCode;
    property PaymentDetails: WideString read Get_PaymentDetails write Set_PaymentDetails;
    property ProviderCode: WideString read Get_ProviderCode write Set_ProviderCode;
    property ProcessingFlag: Integer read Get_ProcessingFlag write Set_ProcessingFlag;
    property FileNameResult: WideString read Get_FileNameResult write Set_FileNameResult;
    property FileNameReport: WideString read Get_FileNameReport write Set_FileNameReport;
    property FileName: WideString read Get_FileName write Set_FileName;
    property PathParameters: WideString read Get_PathParameters write Set_PathParameters;
    property CVV2: WideString read Get_CVV2 write Set_CVV2;
    property AmountFee: WideString read Get_AmountFee write Set_AmountFee;
    property TerminalOutID: WideString read Get_TerminalOutID write Set_TerminalOutID;
    property ReceiptData: WideString read Get_ReceiptData write Set_ReceiptData;
    property EncPinBlock: WideString read Get_EncPinBlock write Set_EncPinBlock;
  published
{$IFDEF LIVE_SERVER_AT_DESIGN_TIME}
    property Server: TTransDataProperties read GetServerProperties;
{$ENDIF}
  end;

{$IFDEF LIVE_SERVER_AT_DESIGN_TIME}
// *********************************************************************//
// OLE Server Properties Proxy Class
// Server Object    : TTransData
// (This object is used by the IDE's Property Inspector to allow editing
//  of the properties of this server)
// *********************************************************************//
 TTransDataProperties = class(TPersistent)
  private
    FServer:    TTransData;
    function    GetDefaultInterface: ITransData;
    constructor Create(AServer: TTransData);
  protected
    function Get_Amount: WideString;
    procedure Set_Amount(const Value: WideString);
    function Get_CurrencyCode: WideString;
    procedure Set_CurrencyCode(const Value: WideString);
    function Get_DateTimeHost: WideString;
    procedure Set_DateTimeHost(const Value: WideString);
    function Get_CardEntryMode: Integer;
    procedure Set_CardEntryMode(Value: Integer);
    function Get_PAN: WideString;
    procedure Set_PAN(const Value: WideString);
    function Get_CardExpiryDate: WideString;
    procedure Set_CardExpiryDate(const Value: WideString);
    function Get_TRACK2: WideString;
    procedure Set_TRACK2(const Value: WideString);
    function Get_AuthorizationCode: WideString;
    procedure Set_AuthorizationCode(const Value: WideString);
    function Get_ReferenceNumber: WideString;
    procedure Set_ReferenceNumber(const Value: WideString);
    function Get_ResponseCodeHost: WideString;
    procedure Set_ResponseCodeHost(const Value: WideString);
    function Get_TextResponse: WideString;
    procedure Set_TextResponse(const Value: WideString);
    function Get_DateTimeCRM: WideString;
    procedure Set_DateTimeCRM(const Value: WideString);
    function Get_TrxID: Integer;
    procedure Set_TrxID(Value: Integer);
    function Get_OrigOperation: Integer;
    procedure Set_OrigOperation(Value: Integer);
    function Get_OperationCode: Integer;
    procedure Set_OperationCode(Value: Integer);
    function Get_TrxIDCRM: Integer;
    procedure Set_TrxIDCRM(Value: Integer);
    function Get_CRMID: WideString;
    procedure Set_CRMID(const Value: WideString);
    function Get_Status: Integer;
    procedure Set_Status(Value: Integer);
    function Get_TrxIDHost: Integer;
    procedure Set_TrxIDHost(Value: Integer);
    function Get_CommodityCode: WideString;
    procedure Set_CommodityCode(const Value: WideString);
    function Get_PaymentDetails: WideString;
    procedure Set_PaymentDetails(const Value: WideString);
    function Get_ProviderCode: WideString;
    procedure Set_ProviderCode(const Value: WideString);
    function Get_ProcessingFlag: Integer;
    procedure Set_ProcessingFlag(Value: Integer);
    function Get_FileNameResult: WideString;
    procedure Set_FileNameResult(const Value: WideString);
    function Get_FileNameReport: WideString;
    procedure Set_FileNameReport(const Value: WideString);
    function Get_FileName: WideString;
    procedure Set_FileName(const Value: WideString);
    function Get_PathParameters: WideString;
    procedure Set_PathParameters(const Value: WideString);
    function Get_CVV2: WideString;
    procedure Set_CVV2(const Value: WideString);
    function Get_AmountFee: WideString;
    procedure Set_AmountFee(const Value: WideString);
    function Get_TerminalOutID: WideString;
    procedure Set_TerminalOutID(const Value: WideString);
    function Get_ReceiptData: WideString;
    procedure Set_ReceiptData(const Value: WideString);
    function Get_EncPinBlock: WideString;
    procedure Set_EncPinBlock(const Value: WideString);
  public
    property DefaultInterface: ITransData read GetDefaultInterface;
  published
    property Amount: WideString read Get_Amount write Set_Amount;
    property CurrencyCode: WideString read Get_CurrencyCode write Set_CurrencyCode;
    property DateTimeHost: WideString read Get_DateTimeHost write Set_DateTimeHost;
    property CardEntryMode: Integer read Get_CardEntryMode write Set_CardEntryMode;
    property PAN: WideString read Get_PAN write Set_PAN;
    property CardExpiryDate: WideString read Get_CardExpiryDate write Set_CardExpiryDate;
    property TRACK2: WideString read Get_TRACK2 write Set_TRACK2;
    property AuthorizationCode: WideString read Get_AuthorizationCode write Set_AuthorizationCode;
    property ReferenceNumber: WideString read Get_ReferenceNumber write Set_ReferenceNumber;
    property ResponseCodeHost: WideString read Get_ResponseCodeHost write Set_ResponseCodeHost;
    property TextResponse: WideString read Get_TextResponse write Set_TextResponse;
    property DateTimeCRM: WideString read Get_DateTimeCRM write Set_DateTimeCRM;
    property TrxID: Integer read Get_TrxID write Set_TrxID;
    property OrigOperation: Integer read Get_OrigOperation write Set_OrigOperation;
    property OperationCode: Integer read Get_OperationCode write Set_OperationCode;
    property TrxIDCRM: Integer read Get_TrxIDCRM write Set_TrxIDCRM;
    property CRMID: WideString read Get_CRMID write Set_CRMID;
    property Status: Integer read Get_Status write Set_Status;
    property TrxIDHost: Integer read Get_TrxIDHost write Set_TrxIDHost;
    property CommodityCode: WideString read Get_CommodityCode write Set_CommodityCode;
    property PaymentDetails: WideString read Get_PaymentDetails write Set_PaymentDetails;
    property ProviderCode: WideString read Get_ProviderCode write Set_ProviderCode;
    property ProcessingFlag: Integer read Get_ProcessingFlag write Set_ProcessingFlag;
    property FileNameResult: WideString read Get_FileNameResult write Set_FileNameResult;
    property FileNameReport: WideString read Get_FileNameReport write Set_FileNameReport;
    property FileName: WideString read Get_FileName write Set_FileName;
    property PathParameters: WideString read Get_PathParameters write Set_PathParameters;
    property CVV2: WideString read Get_CVV2 write Set_CVV2;
    property AmountFee: WideString read Get_AmountFee write Set_AmountFee;
    property TerminalOutID: WideString read Get_TerminalOutID write Set_TerminalOutID;
    property ReceiptData: WideString read Get_ReceiptData write Set_ReceiptData;
    property EncPinBlock: WideString read Get_EncPinBlock write Set_EncPinBlock;
  end;
{$ENDIF}


// *********************************************************************//
// The Class CoTransSender provides a Create and CreateRemote method to          
// create instances of the default interface ITransSender exposed by              
// the CoClass TransSender. The functions are intended to be used by             
// clients wishing to automate the CoClass objects exposed by the         
// server of this typelibrary.                                            
// *********************************************************************//
  CoTransSender = class
    class function Create: ITransSender;
    class function CreateRemote(const MachineName: string): ITransSender;
  end;

  TTransSenderOnExchange = procedure(ASender: TObject; Result: Integer) of object;
  TTransSenderOnPINExchange = procedure(ASender: TObject; const PAN: WideString; 
                                                          const EWK: WideString; MkIndex: Integer) of object;


// *********************************************************************//
// OLE Server Proxy class declaration
// Server Object    : TTransSender
// Help String      : Transaction Sender Object
// Default Interface: ITransSender
// Def. Intf. DISP? : No
// Event   Interface: ITransSenderEvents
// TypeFlags        : (2) CanCreate
// *********************************************************************//
{$IFDEF LIVE_SERVER_AT_DESIGN_TIME}
  TTransSenderProperties= class;
{$ENDIF}
  TTransSender = class(TOleServer)
  private
    FOnExchange: TTransSenderOnExchange;
    FOnPINExchange: TTransSenderOnPINExchange;
    FIntf:        ITransSender;
{$IFDEF LIVE_SERVER_AT_DESIGN_TIME}
    FProps:       TTransSenderProperties;
    function      GetServerProperties: TTransSenderProperties;
{$ENDIF}
    function      GetDefaultInterface: ITransSender;
  protected
    procedure InitServerData; override;
    procedure InvokeEvent(DispID: TDispID; var Params: TVariantArray); override;
    function Get_ErrorDescription: WideString;
    function Get_ErrorCode: Integer;
  public
    constructor Create(AOwner: TComponent); override;
    destructor  Destroy; override;
    procedure Connect; override;
    procedure ConnectTo(svrIntf: ITransSender);
    procedure Disconnect; override;
    function InitResources: Integer;
    procedure FreeResources;
    function SetChannelParam(const IPAddress: WideString; IPPort: Integer; 
                             const X25Script: WideString): Integer;
    function SetProtocolParam(TimeoutACK: Integer; TimeoutPacket: Integer; CountNAK: Integer; 
                              PacketSize: Integer): Integer;
    function Exchange(var Request: ITransData; var Response: ITransData; Timeout: Integer): Integer;
    function SetControlServerParam(const IPAddress: WideString; IPPort: Integer; Timeout: Integer): Integer;
    function ControlServerStart: Integer;
    function ControlServerStop: Integer;
    function CancelExchange: Integer;
    property DefaultInterface: ITransSender read GetDefaultInterface;
    property ErrorDescription: WideString read Get_ErrorDescription;
    property ErrorCode: Integer read Get_ErrorCode;
  published
{$IFDEF LIVE_SERVER_AT_DESIGN_TIME}
    property Server: TTransSenderProperties read GetServerProperties;
{$ENDIF}
    property OnExchange: TTransSenderOnExchange read FOnExchange write FOnExchange;
    property OnPINExchange: TTransSenderOnPINExchange read FOnPINExchange write FOnPINExchange;
  end;

{$IFDEF LIVE_SERVER_AT_DESIGN_TIME}
// *********************************************************************//
// OLE Server Properties Proxy Class
// Server Object    : TTransSender
// (This object is used by the IDE's Property Inspector to allow editing
//  of the properties of this server)
// *********************************************************************//
 TTransSenderProperties = class(TPersistent)
  private
    FServer:    TTransSender;
    function    GetDefaultInterface: ITransSender;
    constructor Create(AServer: TTransSender);
  protected
    function Get_ErrorDescription: WideString;
    function Get_ErrorCode: Integer;
  public
    property DefaultInterface: ITransSender read GetDefaultInterface;
  published
  end;
{$ENDIF}


procedure Register;

resourcestring
  dtlServerPage = 'ActiveX';

  dtlOcxPage = 'ActiveX';

implementation

uses ComObj;

class function CoTransData.Create: ITransData;
begin
  Result := CreateComObject(CLASS_TransData) as ITransData;
end;

class function CoTransData.CreateRemote(const MachineName: string): ITransData;
begin
  Result := CreateRemoteComObject(MachineName, CLASS_TransData) as ITransData;
end;

procedure TTransData.InitServerData;
const
  CServerData: TServerData = (
    ClassID:   '{D7F12E9A-14D1-4437-A0FC-0D5C4113C660}';
    IntfIID:   '{52D0C76F-CBE0-4A88-B808-B05D4BECC834}';
    EventIID:  '';
    LicenseKey: nil;
    Version: 500);
begin
  ServerData := @CServerData;
end;

procedure TTransData.Connect;
var
  punk: IUnknown;
begin
  if FIntf = nil then
  begin
    punk := GetServer;
    Fintf:= punk as ITransData;
  end;
end;

procedure TTransData.ConnectTo(svrIntf: ITransData);
begin
  Disconnect;
  FIntf := svrIntf;
end;

procedure TTransData.DisConnect;
begin
  if Fintf <> nil then
  begin
    FIntf := nil;
  end;
end;

function TTransData.GetDefaultInterface: ITransData;
begin
  if FIntf = nil then
    Connect;
  Assert(FIntf <> nil, 'DefaultInterface is NULL. Component is not connected to Server. You must call ''Connect'' or ''ConnectTo'' before this operation');
  Result := FIntf;
end;

constructor TTransData.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
{$IFDEF LIVE_SERVER_AT_DESIGN_TIME}
  FProps := TTransDataProperties.Create(Self);
{$ENDIF}
end;

destructor TTransData.Destroy;
begin
{$IFDEF LIVE_SERVER_AT_DESIGN_TIME}
  FProps.Free;
{$ENDIF}
  inherited Destroy;
end;

{$IFDEF LIVE_SERVER_AT_DESIGN_TIME}
function TTransData.GetServerProperties: TTransDataProperties;
begin
  Result := FProps;
end;
{$ENDIF}

function TTransData.Get_Amount: WideString;
begin
    Result := DefaultInterface.Amount;
end;

procedure TTransData.Set_Amount(const Value: WideString);
  { Warning: The property Amount has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.Amount := Value;
end;

function TTransData.Get_CurrencyCode: WideString;
begin
    Result := DefaultInterface.CurrencyCode;
end;

procedure TTransData.Set_CurrencyCode(const Value: WideString);
  { Warning: The property CurrencyCode has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.CurrencyCode := Value;
end;

function TTransData.Get_DateTimeHost: WideString;
begin
    Result := DefaultInterface.DateTimeHost;
end;

procedure TTransData.Set_DateTimeHost(const Value: WideString);
  { Warning: The property DateTimeHost has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.DateTimeHost := Value;
end;

function TTransData.Get_CardEntryMode: Integer;
begin
    Result := DefaultInterface.CardEntryMode;
end;

procedure TTransData.Set_CardEntryMode(Value: Integer);
begin
  DefaultInterface.Set_CardEntryMode(Value);
end;

function TTransData.Get_PAN: WideString;
begin
    Result := DefaultInterface.PAN;
end;

procedure TTransData.Set_PAN(const Value: WideString);
  { Warning: The property PAN has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.PAN := Value;
end;

function TTransData.Get_CardExpiryDate: WideString;
begin
    Result := DefaultInterface.CardExpiryDate;
end;

procedure TTransData.Set_CardExpiryDate(const Value: WideString);
  { Warning: The property CardExpiryDate has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.CardExpiryDate := Value;
end;

function TTransData.Get_TRACK2: WideString;
begin
    Result := DefaultInterface.TRACK2;
end;

procedure TTransData.Set_TRACK2(const Value: WideString);
  { Warning: The property TRACK2 has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.TRACK2 := Value;
end;

function TTransData.Get_AuthorizationCode: WideString;
begin
    Result := DefaultInterface.AuthorizationCode;
end;

procedure TTransData.Set_AuthorizationCode(const Value: WideString);
  { Warning: The property AuthorizationCode has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.AuthorizationCode := Value;
end;

function TTransData.Get_ReferenceNumber: WideString;
begin
    Result := DefaultInterface.ReferenceNumber;
end;

procedure TTransData.Set_ReferenceNumber(const Value: WideString);
  { Warning: The property ReferenceNumber has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.ReferenceNumber := Value;
end;

function TTransData.Get_ResponseCodeHost: WideString;
begin
    Result := DefaultInterface.ResponseCodeHost;
end;

procedure TTransData.Set_ResponseCodeHost(const Value: WideString);
  { Warning: The property ResponseCodeHost has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.ResponseCodeHost := Value;
end;

function TTransData.Get_TextResponse: WideString;
begin
    Result := DefaultInterface.TextResponse;
end;

procedure TTransData.Set_TextResponse(const Value: WideString);
  { Warning: The property TextResponse has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.TextResponse := Value;
end;

function TTransData.Get_DateTimeCRM: WideString;
begin
    Result := DefaultInterface.DateTimeCRM;
end;

procedure TTransData.Set_DateTimeCRM(const Value: WideString);
  { Warning: The property DateTimeCRM has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.DateTimeCRM := Value;
end;

function TTransData.Get_TrxID: Integer;
begin
    Result := DefaultInterface.TrxID;
end;

procedure TTransData.Set_TrxID(Value: Integer);
begin
  DefaultInterface.Set_TrxID(Value);
end;

function TTransData.Get_OrigOperation: Integer;
begin
    Result := DefaultInterface.OrigOperation;
end;

procedure TTransData.Set_OrigOperation(Value: Integer);
begin
  DefaultInterface.Set_OrigOperation(Value);
end;

function TTransData.Get_OperationCode: Integer;
begin
    Result := DefaultInterface.OperationCode;
end;

procedure TTransData.Set_OperationCode(Value: Integer);
begin
  DefaultInterface.Set_OperationCode(Value);
end;

function TTransData.Get_TrxIDCRM: Integer;
begin
    Result := DefaultInterface.TrxIDCRM;
end;

procedure TTransData.Set_TrxIDCRM(Value: Integer);
begin
  DefaultInterface.Set_TrxIDCRM(Value);
end;

function TTransData.Get_CRMID: WideString;
begin
    Result := DefaultInterface.CRMID;
end;

procedure TTransData.Set_CRMID(const Value: WideString);
  { Warning: The property CRMID has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.CRMID := Value;
end;

function TTransData.Get_Status: Integer;
begin
    Result := DefaultInterface.Status;
end;

procedure TTransData.Set_Status(Value: Integer);
begin
  DefaultInterface.Set_Status(Value);
end;

function TTransData.Get_TrxIDHost: Integer;
begin
    Result := DefaultInterface.TrxIDHost;
end;

procedure TTransData.Set_TrxIDHost(Value: Integer);
begin
  DefaultInterface.Set_TrxIDHost(Value);
end;

function TTransData.Get_CommodityCode: WideString;
begin
    Result := DefaultInterface.CommodityCode;
end;

procedure TTransData.Set_CommodityCode(const Value: WideString);
  { Warning: The property CommodityCode has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.CommodityCode := Value;
end;

function TTransData.Get_PaymentDetails: WideString;
begin
    Result := DefaultInterface.PaymentDetails;
end;

procedure TTransData.Set_PaymentDetails(const Value: WideString);
  { Warning: The property PaymentDetails has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.PaymentDetails := Value;
end;

function TTransData.Get_ProviderCode: WideString;
begin
    Result := DefaultInterface.ProviderCode;
end;

procedure TTransData.Set_ProviderCode(const Value: WideString);
  { Warning: The property ProviderCode has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.ProviderCode := Value;
end;

function TTransData.Get_ProcessingFlag: Integer;
begin
    Result := DefaultInterface.ProcessingFlag;
end;

procedure TTransData.Set_ProcessingFlag(Value: Integer);
begin
  DefaultInterface.Set_ProcessingFlag(Value);
end;

function TTransData.Get_FileNameResult: WideString;
begin
    Result := DefaultInterface.FileNameResult;
end;

procedure TTransData.Set_FileNameResult(const Value: WideString);
  { Warning: The property FileNameResult has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.FileNameResult := Value;
end;

function TTransData.Get_FileNameReport: WideString;
begin
    Result := DefaultInterface.FileNameReport;
end;

procedure TTransData.Set_FileNameReport(const Value: WideString);
  { Warning: The property FileNameReport has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.FileNameReport := Value;
end;

function TTransData.Get_FileName: WideString;
begin
    Result := DefaultInterface.FileName;
end;

procedure TTransData.Set_FileName(const Value: WideString);
  { Warning: The property FileName has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.FileName := Value;
end;

function TTransData.Get_PathParameters: WideString;
begin
    Result := DefaultInterface.PathParameters;
end;

procedure TTransData.Set_PathParameters(const Value: WideString);
  { Warning: The property PathParameters has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.PathParameters := Value;
end;

function TTransData.Get_CVV2: WideString;
begin
    Result := DefaultInterface.CVV2;
end;

procedure TTransData.Set_CVV2(const Value: WideString);
  { Warning: The property CVV2 has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.CVV2 := Value;
end;

function TTransData.Get_AmountFee: WideString;
begin
    Result := DefaultInterface.AmountFee;
end;

procedure TTransData.Set_AmountFee(const Value: WideString);
  { Warning: The property AmountFee has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.AmountFee := Value;
end;

function TTransData.Get_TerminalOutID: WideString;
begin
    Result := DefaultInterface.TerminalOutID;
end;

procedure TTransData.Set_TerminalOutID(const Value: WideString);
  { Warning: The property TerminalOutID has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.TerminalOutID := Value;
end;

function TTransData.Get_ReceiptData: WideString;
begin
    Result := DefaultInterface.ReceiptData;
end;

procedure TTransData.Set_ReceiptData(const Value: WideString);
  { Warning: The property ReceiptData has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.ReceiptData := Value;
end;

function TTransData.Get_EncPinBlock: WideString;
begin
    Result := DefaultInterface.EncPinBlock;
end;

procedure TTransData.Set_EncPinBlock(const Value: WideString);
  { Warning: The property EncPinBlock has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.EncPinBlock := Value;
end;

{$IFDEF LIVE_SERVER_AT_DESIGN_TIME}
constructor TTransDataProperties.Create(AServer: TTransData);
begin
  inherited Create;
  FServer := AServer;
end;

function TTransDataProperties.GetDefaultInterface: ITransData;
begin
  Result := FServer.DefaultInterface;
end;

function TTransDataProperties.Get_Amount: WideString;
begin
    Result := DefaultInterface.Amount;
end;

procedure TTransDataProperties.Set_Amount(const Value: WideString);
  { Warning: The property Amount has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.Amount := Value;
end;

function TTransDataProperties.Get_CurrencyCode: WideString;
begin
    Result := DefaultInterface.CurrencyCode;
end;

procedure TTransDataProperties.Set_CurrencyCode(const Value: WideString);
  { Warning: The property CurrencyCode has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.CurrencyCode := Value;
end;

function TTransDataProperties.Get_DateTimeHost: WideString;
begin
    Result := DefaultInterface.DateTimeHost;
end;

procedure TTransDataProperties.Set_DateTimeHost(const Value: WideString);
  { Warning: The property DateTimeHost has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.DateTimeHost := Value;
end;

function TTransDataProperties.Get_CardEntryMode: Integer;
begin
    Result := DefaultInterface.CardEntryMode;
end;

procedure TTransDataProperties.Set_CardEntryMode(Value: Integer);
begin
  DefaultInterface.Set_CardEntryMode(Value);
end;

function TTransDataProperties.Get_PAN: WideString;
begin
    Result := DefaultInterface.PAN;
end;

procedure TTransDataProperties.Set_PAN(const Value: WideString);
  { Warning: The property PAN has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.PAN := Value;
end;

function TTransDataProperties.Get_CardExpiryDate: WideString;
begin
    Result := DefaultInterface.CardExpiryDate;
end;

procedure TTransDataProperties.Set_CardExpiryDate(const Value: WideString);
  { Warning: The property CardExpiryDate has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.CardExpiryDate := Value;
end;

function TTransDataProperties.Get_TRACK2: WideString;
begin
    Result := DefaultInterface.TRACK2;
end;

procedure TTransDataProperties.Set_TRACK2(const Value: WideString);
  { Warning: The property TRACK2 has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.TRACK2 := Value;
end;

function TTransDataProperties.Get_AuthorizationCode: WideString;
begin
    Result := DefaultInterface.AuthorizationCode;
end;

procedure TTransDataProperties.Set_AuthorizationCode(const Value: WideString);
  { Warning: The property AuthorizationCode has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.AuthorizationCode := Value;
end;

function TTransDataProperties.Get_ReferenceNumber: WideString;
begin
    Result := DefaultInterface.ReferenceNumber;
end;

procedure TTransDataProperties.Set_ReferenceNumber(const Value: WideString);
  { Warning: The property ReferenceNumber has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.ReferenceNumber := Value;
end;

function TTransDataProperties.Get_ResponseCodeHost: WideString;
begin
    Result := DefaultInterface.ResponseCodeHost;
end;

procedure TTransDataProperties.Set_ResponseCodeHost(const Value: WideString);
  { Warning: The property ResponseCodeHost has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.ResponseCodeHost := Value;
end;

function TTransDataProperties.Get_TextResponse: WideString;
begin
    Result := DefaultInterface.TextResponse;
end;

procedure TTransDataProperties.Set_TextResponse(const Value: WideString);
  { Warning: The property TextResponse has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.TextResponse := Value;
end;

function TTransDataProperties.Get_DateTimeCRM: WideString;
begin
    Result := DefaultInterface.DateTimeCRM;
end;

procedure TTransDataProperties.Set_DateTimeCRM(const Value: WideString);
  { Warning: The property DateTimeCRM has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.DateTimeCRM := Value;
end;

function TTransDataProperties.Get_TrxID: Integer;
begin
    Result := DefaultInterface.TrxID;
end;

procedure TTransDataProperties.Set_TrxID(Value: Integer);
begin
  DefaultInterface.Set_TrxID(Value);
end;

function TTransDataProperties.Get_OrigOperation: Integer;
begin
    Result := DefaultInterface.OrigOperation;
end;

procedure TTransDataProperties.Set_OrigOperation(Value: Integer);
begin
  DefaultInterface.Set_OrigOperation(Value);
end;

function TTransDataProperties.Get_OperationCode: Integer;
begin
    Result := DefaultInterface.OperationCode;
end;

procedure TTransDataProperties.Set_OperationCode(Value: Integer);
begin
  DefaultInterface.Set_OperationCode(Value);
end;

function TTransDataProperties.Get_TrxIDCRM: Integer;
begin
    Result := DefaultInterface.TrxIDCRM;
end;

procedure TTransDataProperties.Set_TrxIDCRM(Value: Integer);
begin
  DefaultInterface.Set_TrxIDCRM(Value);
end;

function TTransDataProperties.Get_CRMID: WideString;
begin
    Result := DefaultInterface.CRMID;
end;

procedure TTransDataProperties.Set_CRMID(const Value: WideString);
  { Warning: The property CRMID has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.CRMID := Value;
end;

function TTransDataProperties.Get_Status: Integer;
begin
    Result := DefaultInterface.Status;
end;

procedure TTransDataProperties.Set_Status(Value: Integer);
begin
  DefaultInterface.Set_Status(Value);
end;

function TTransDataProperties.Get_TrxIDHost: Integer;
begin
    Result := DefaultInterface.TrxIDHost;
end;

procedure TTransDataProperties.Set_TrxIDHost(Value: Integer);
begin
  DefaultInterface.Set_TrxIDHost(Value);
end;

function TTransDataProperties.Get_CommodityCode: WideString;
begin
    Result := DefaultInterface.CommodityCode;
end;

procedure TTransDataProperties.Set_CommodityCode(const Value: WideString);
  { Warning: The property CommodityCode has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.CommodityCode := Value;
end;

function TTransDataProperties.Get_PaymentDetails: WideString;
begin
    Result := DefaultInterface.PaymentDetails;
end;

procedure TTransDataProperties.Set_PaymentDetails(const Value: WideString);
  { Warning: The property PaymentDetails has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.PaymentDetails := Value;
end;

function TTransDataProperties.Get_ProviderCode: WideString;
begin
    Result := DefaultInterface.ProviderCode;
end;

procedure TTransDataProperties.Set_ProviderCode(const Value: WideString);
  { Warning: The property ProviderCode has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.ProviderCode := Value;
end;

function TTransDataProperties.Get_ProcessingFlag: Integer;
begin
    Result := DefaultInterface.ProcessingFlag;
end;

procedure TTransDataProperties.Set_ProcessingFlag(Value: Integer);
begin
  DefaultInterface.Set_ProcessingFlag(Value);
end;

function TTransDataProperties.Get_FileNameResult: WideString;
begin
    Result := DefaultInterface.FileNameResult;
end;

procedure TTransDataProperties.Set_FileNameResult(const Value: WideString);
  { Warning: The property FileNameResult has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.FileNameResult := Value;
end;

function TTransDataProperties.Get_FileNameReport: WideString;
begin
    Result := DefaultInterface.FileNameReport;
end;

procedure TTransDataProperties.Set_FileNameReport(const Value: WideString);
  { Warning: The property FileNameReport has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.FileNameReport := Value;
end;

function TTransDataProperties.Get_FileName: WideString;
begin
    Result := DefaultInterface.FileName;
end;

procedure TTransDataProperties.Set_FileName(const Value: WideString);
  { Warning: The property FileName has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.FileName := Value;
end;

function TTransDataProperties.Get_PathParameters: WideString;
begin
    Result := DefaultInterface.PathParameters;
end;

procedure TTransDataProperties.Set_PathParameters(const Value: WideString);
  { Warning: The property PathParameters has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.PathParameters := Value;
end;

function TTransDataProperties.Get_CVV2: WideString;
begin
    Result := DefaultInterface.CVV2;
end;

procedure TTransDataProperties.Set_CVV2(const Value: WideString);
  { Warning: The property CVV2 has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.CVV2 := Value;
end;

function TTransDataProperties.Get_AmountFee: WideString;
begin
    Result := DefaultInterface.AmountFee;
end;

procedure TTransDataProperties.Set_AmountFee(const Value: WideString);
  { Warning: The property AmountFee has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.AmountFee := Value;
end;

function TTransDataProperties.Get_TerminalOutID: WideString;
begin
    Result := DefaultInterface.TerminalOutID;
end;

procedure TTransDataProperties.Set_TerminalOutID(const Value: WideString);
  { Warning: The property TerminalOutID has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.TerminalOutID := Value;
end;

function TTransDataProperties.Get_ReceiptData: WideString;
begin
    Result := DefaultInterface.ReceiptData;
end;

procedure TTransDataProperties.Set_ReceiptData(const Value: WideString);
  { Warning: The property ReceiptData has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.ReceiptData := Value;
end;

function TTransDataProperties.Get_EncPinBlock: WideString;
begin
    Result := DefaultInterface.EncPinBlock;
end;

procedure TTransDataProperties.Set_EncPinBlock(const Value: WideString);
  { Warning: The property EncPinBlock has a setter and a getter whose
    types do not match. Delphi was unable to generate a property of
    this sort and so is using a Variant as a passthrough. }
var
  InterfaceVariant: OleVariant;
begin
  InterfaceVariant := DefaultInterface;
  InterfaceVariant.EncPinBlock := Value;
end;

{$ENDIF}

class function CoTransSender.Create: ITransSender;
begin
  Result := CreateComObject(CLASS_TransSender) as ITransSender;
end;

class function CoTransSender.CreateRemote(const MachineName: string): ITransSender;
begin
  Result := CreateRemoteComObject(MachineName, CLASS_TransSender) as ITransSender;
end;

procedure TTransSender.InitServerData;
const
  CServerData: TServerData = (
    ClassID:   '{8B6FC1F7-4737-474D-9324-9C7B7CF31EB3}';
    IntfIID:   '{9E13876D-EDE8-4B1B-BE89-B8497B693C6B}';
    EventIID:  '{55E43BB7-1E1A-4358-9F65-CA5CD866B793}';
    LicenseKey: nil;
    Version: 500);
begin
  ServerData := @CServerData;
end;

procedure TTransSender.Connect;
var
  punk: IUnknown;
begin
  if FIntf = nil then
  begin
    punk := GetServer;
    ConnectEvents(punk);
    Fintf:= punk as ITransSender;
  end;
end;

procedure TTransSender.ConnectTo(svrIntf: ITransSender);
begin
  Disconnect;
  FIntf := svrIntf;
  ConnectEvents(FIntf);
end;

procedure TTransSender.DisConnect;
begin
  if Fintf <> nil then
  begin
    DisconnectEvents(FIntf);
    FIntf := nil;
  end;
end;

function TTransSender.GetDefaultInterface: ITransSender;
begin
  if FIntf = nil then
    Connect;
  Assert(FIntf <> nil, 'DefaultInterface is NULL. Component is not connected to Server. You must call ''Connect'' or ''ConnectTo'' before this operation');
  Result := FIntf;
end;

constructor TTransSender.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
{$IFDEF LIVE_SERVER_AT_DESIGN_TIME}
  FProps := TTransSenderProperties.Create(Self);
{$ENDIF}
end;

destructor TTransSender.Destroy;
begin
{$IFDEF LIVE_SERVER_AT_DESIGN_TIME}
  FProps.Free;
{$ENDIF}
  inherited Destroy;
end;

{$IFDEF LIVE_SERVER_AT_DESIGN_TIME}
function TTransSender.GetServerProperties: TTransSenderProperties;
begin
  Result := FProps;
end;
{$ENDIF}

procedure TTransSender.InvokeEvent(DispID: TDispID; var Params: TVariantArray);
begin
  case DispID of
    -1: Exit;  // DISPID_UNKNOWN
    1: if Assigned(FOnExchange) then
         FOnExchange(Self, Params[0] {Integer});
    2: if Assigned(FOnPINExchange) then
         FOnPINExchange(Self,
                        Params[0] {const WideString},
                        Params[1] {const WideString},
                        Params[2] {Integer});
  end; {case DispID}
end;

function TTransSender.Get_ErrorDescription: WideString;
begin
    Result := DefaultInterface.ErrorDescription;
end;

function TTransSender.Get_ErrorCode: Integer;
begin
    Result := DefaultInterface.ErrorCode;
end;

function TTransSender.InitResources: Integer;
begin
  Result := DefaultInterface.InitResources;
end;

procedure TTransSender.FreeResources;
begin
  DefaultInterface.FreeResources;
end;

function TTransSender.SetChannelParam(const IPAddress: WideString; IPPort: Integer; 
                                      const X25Script: WideString): Integer;
begin
  Result := DefaultInterface.SetChannelParam(IPAddress, IPPort, X25Script);
end;

function TTransSender.SetProtocolParam(TimeoutACK: Integer; TimeoutPacket: Integer; 
                                       CountNAK: Integer; PacketSize: Integer): Integer;
begin
  Result := DefaultInterface.SetProtocolParam(TimeoutACK, TimeoutPacket, CountNAK, PacketSize);
end;

function TTransSender.Exchange(var Request: ITransData; var Response: ITransData; Timeout: Integer): Integer;
begin
  Result := DefaultInterface.Exchange(Request, Response, Timeout);
end;

function TTransSender.SetControlServerParam(const IPAddress: WideString; IPPort: Integer; 
                                            Timeout: Integer): Integer;
begin
  Result := DefaultInterface.SetControlServerParam(IPAddress, IPPort, Timeout);
end;

function TTransSender.ControlServerStart: Integer;
begin
  Result := DefaultInterface.ControlServerStart;
end;

function TTransSender.ControlServerStop: Integer;
begin
  Result := DefaultInterface.ControlServerStop;
end;

function TTransSender.CancelExchange: Integer;
begin
  Result := DefaultInterface.CancelExchange;
end;

{$IFDEF LIVE_SERVER_AT_DESIGN_TIME}
constructor TTransSenderProperties.Create(AServer: TTransSender);
begin
  inherited Create;
  FServer := AServer;
end;

function TTransSenderProperties.GetDefaultInterface: ITransSender;
begin
  Result := FServer.DefaultInterface;
end;

function TTransSenderProperties.Get_ErrorDescription: WideString;
begin
    Result := DefaultInterface.ErrorDescription;
end;

function TTransSenderProperties.Get_ErrorCode: Integer;
begin
    Result := DefaultInterface.ErrorCode;
end;

{$ENDIF}

procedure Register;
begin
  RegisterComponents(dtlServerPage, [TTransData, TTransSender]);
end;

end.
