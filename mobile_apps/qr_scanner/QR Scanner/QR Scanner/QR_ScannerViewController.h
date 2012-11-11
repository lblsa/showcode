/** @file QR_ScannerViewController.h
 *  @brief Заголовочный файл ViewController'а основного View приложения. Создан 14.10.11
 *  
 *  Заголовочный файл ViewController'а основного View приложения. Отвечает за инициализацию 
 *  переменных класса, также объявляет, что данный класс удовлетворяет протоколам классов 
 *  ZBarReader (сторонний класс, отвечает за сканирование и распознавание QR-кодов), UITableView 
 *  (стандартный класс, отвечает за работу с табличным представлением), UITableViewDataSource 
 *  (протокол отвечает за источник данных для табличного представления).
 */

#import <UIKit/UIKit.h>

#define kFileName       @"data.plist"

@class DataFetcher, Ticket;

@interface QR_ScannerViewController : UIViewController <ZBarReaderDelegate, UITableViewDelegate, UITableViewDataSource, UIActionSheetDelegate>{
    /// Переменная для хранения распознанного текста QR-кода
    NSString *qrText;
    /// Переменная, отвечающая за табличное представление 
    UITableView *mainTable;
    /// Переменная, сканируем билет - YES, сканируем мероприятие - NO 
    BOOL scanningTicket;
    /// Переменная с названием мероприятия
    NSString *ourEventName;
    /// Переменная с кодом мероприятия
    NSString *ourEventCode;
    /// Переменная кода статуса в информационной строке
    NSInteger statusCode;
    /// Объект для получения данных с сервера
    DataFetcher *dataFetcher;
    /// Переменная-флаг непрерывного сканирования
    BOOL continuosScan;
    /// Переменная-счетчик отсканированных билетов
    NSInteger ticketsCount;
    /// Переменная-счетчик отсканированных билетов (со всех устройств)
    NSInteger ticketsTotalCount;
    /// Переменная-флаг наличия отсканированного, но не пропущенного билета
    BOOL ticketNeedsToBeMarked;
    /// Переменная билета - нужна для хранения данных текущего отсканированного билета
    Ticket *currentTicket;
    /// Переменная флажок для исключения сканирования нескольких билетов подряд в авто-режиме
    BOOL scanNextTicket;
}

// Инициализация переменных
@property (nonatomic, copy) NSString *qrText;
@property (nonatomic, retain) IBOutlet UITableView *mainTable;
@property (nonatomic, assign) BOOL scanningTicket;
@property (nonatomic, copy) NSString *ourEventName;
@property (nonatomic, copy) NSString *ourEventCode;
@property (nonatomic, retain) DataFetcher *dataFetcher;
@property (nonatomic, assign) BOOL continuosScan;
@property (nonatomic, assign) BOOL ticketNeedsToBeMarked;
@property (nonatomic) NSInteger ticketsCount;
@property (nonatomic) NSInteger ticketsTotalCount;
@property (nonatomic) NSInteger statusCode;
@property (nonatomic, retain) Ticket *currentTicket;
@property (nonatomic, assign) BOOL scanNextTicket;

/// Объявление метода сканирования и распознавания QR-кода
- (void)scanButtonTapped;
- (NSString *)dataFilePath;
- (void)applicationWillResignActive:(NSNotification *)notification;
- (void)setStatusCodeWithAlert:(NSInteger)code;
- (void)getTotalMarkedTickets;
- (void)clearCurrentTicket;
- (void)saveCurrentTicketWithURL:(NSString *)ticketURL withID:(NSString *)ticketID withEventID:(NSString *)eventID withStatus:(NSInteger)returnCode withServerResponse:(NSString *)serverResponseString;
- (void)saveTicketWithURL:(NSString *)ticketURL withID:(NSString *)ticketID withEventID:(NSString *)eventID withStatus:(NSInteger)returnCode withServerResponse:(NSString *)serverResponseString;
- (void)showAlertWithTitle:(NSString *)title withMessage:(NSString *)message withButton:(NSString *)button;

@end
