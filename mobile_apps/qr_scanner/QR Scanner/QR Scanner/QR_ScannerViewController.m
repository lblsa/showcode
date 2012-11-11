/** @file QR_ScannerViewController.h
 *  @brief Файл ViewController'а основного View приложения. Создан 14.10.11
 *  
 *  Файл ViewController'а основного View приложения. Основная логика работы приложения 
 *  находится здесь. Файл разбит на три секции, первая отвечает за методы табличного 
 *  представления, вторая - за методы сканирования и распознавания QR-кода, третья - 
 *  за методы управления памятью и автоматическим поворотом окна приложения при изменении 
 *  ориентации устройства.
 */

#import "QR_ScannerViewController.h"
#import <UIKit/UIKit.h>
#import <QuartzCore/QuartzCore.h>
#import "DataFetcher.h"
#import "QR_ScannerAppDelegate.h"
#import "Ticket.h"

/// Константы для красивого вывода текста в строки таблицы с переменной высотой
#define FONT_SIZE               16.0f   /// < Размер шрифта ячейки
#define CELL_CONTENT_WIDTH      320.0f  /// < Ширина ячейки 
#define CELL_CONTENT_MARGIN     14.0f   /// < Величина отступа содержимого таблицы от родительского элемента
/// Статусные сообщения для информирования пользователя
#define STATUS_MINUS_1 @"Указанный билет не существует"                             // statusCode = -1
#define STATUS_MINUS_2 @"Возникла проблема соединения с сервером"                   // statusCode = -2
#define STATUS_MINUS_3 @"Указанное мероприятие не существует"                       // statusCode = -3
#define STATUS_0 @"Перед сканированием билетов задайте мероприятие"                 // statusCode = 0
#define STATUS_1 @"Мероприятие задано, теперь можно сканировать билеты"             // statusCode = 1
#define STATUS_2 @"Билет забронирован, но не оплачен"                               // statusCode = 2
#define STATUS_3 @"Билет оплачен и действителен"                                    // statusCode = 3
#define STATUS_4 @"Билет не оплачен, бронь просрочена"                              // statusCode = 4
#define STATUS_5 @"Билет уже использован"                                           // statusCode = 5
#define STATUS_6 @"Билет сегодня не действует"                                      // statusCode = 6
/// Константы для обращения к серверу
//#define HTTP_REQUEST_ADDRESS_PART1 @"http://uat.showcode.ru/"
#define HTTP_REQUEST_ADDRESS_PART1 @"http://showcode.ru/"
#define HTTP_REQUEST_ADDRESS_PART2 @"events/webApi?ticket="
#define HTTP_REQUEST_ADDRESS_PART4 @"&event="
#define HTTP_REQUEST_ADDRESS_TICKET_VIEW @"http://showcode.ru/ticket/view/"
#define HTTP_REQUEST_GET_EVENT_PART1 @"events/webApi?event="
#define HTTP_REQUEST_GET_EVENT_PART2 @"&name=1"
#define HTTP_REQUEST_GET_EVENT_PART3 @"&count=1"
#define HTTP_REQUEST_MARK_TICKET @"&markticket=1"
#define HTTP_REQUEST_DONT_MARK_TICKET @"&markticket=0"
#define QR_EVENT_LENGTH         39
#define QR_EVENT_API_LENGTH     7
//#define QR_TICKET_LENGTH        43
#define QR_TICKET_API_LENGTH    31

@implementation QR_ScannerViewController

@synthesize qrText;
@synthesize mainTable;
@synthesize scanningTicket;
@synthesize ourEventName;
@synthesize ourEventCode;
@synthesize dataFetcher;
@synthesize continuosScan;
@synthesize ticketsCount;
@synthesize statusCode;
@synthesize ticketsTotalCount;
@synthesize ticketNeedsToBeMarked;
@synthesize currentTicket;
@synthesize scanNextTicket;

#pragma mark - ActionSheet Delegate Methods
/// Реализация метода делегата списка действий по подтверждению выбора мероприятия
- (void)actionSheet:(UIActionSheet *)actionSheet didDismissWithButtonIndex:(NSInteger)buttonIndex {
    if (([actionSheet.title isEqualToString:@"Вы хотите задать мероприятие?"]) && (buttonIndex == [actionSheet destructiveButtonIndex])) {
        self.scanningTicket = NO;
        [self scanButtonTapped];
    }
}

#pragma mark - TableView Delegate Methods
/// Стандартный метод задания заголовков секций таблицы, секции начинаются с 0.
- (NSString *)tableView:(UITableView *)tv titleForHeaderInSection:(NSInteger)section {
    return @"";
}

/// Стандартный метод задания нижних подписей секций таблицы, секции начинаются с 0.
- (NSString *)tableView:(UITableView *)tv titleForFooterInSection:(NSInteger)section {
    NSString *tempEventName = [NSString string];
    if ([self.ourEventName isEqualToString:@""] == YES)
        tempEventName = @"Не задано";
    else
        tempEventName = self.ourEventName;
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    if ([defaults boolForKey:@"autoLetIn"] == NO) {
        if (section == 4)
            return [NSString stringWithFormat:@"Мероприятие: %@", tempEventName];
    } else {
        if (section == 3)
            return [NSString stringWithFormat:@"Мероприятие: %@", tempEventName];
    }
    return @"";
}

/// Стандартный метод задания количества секций таблицы
- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView { 
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    if ([defaults boolForKey:@"autoLetIn"] == NO) {
        return 5;
    }
    return 4;
}

/// Стандартный метод задания количества ячеек в секции таблицы, определяемой переменной @a section
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section { 
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    if ([defaults boolForKey:@"autoLetIn"] == NO) {
        if (section == 3)
            return 2;
    } else {
        if (section == 2)
            return 2;
    }
    return 1;
}

/// Стандартный метод задания высоты строки таблицы, заданной в переменной @a indexPath
- (CGFloat) tableView: (UITableView *) tableView heightForRowAtIndexPath: (NSIndexPath *) indexPath {
    if ([indexPath section] == 0) {
        NSString *text = STATUS_0;
        CGFloat height = [text sizeWithFont:[UIFont systemFontOfSize:FONT_SIZE] 
                          constrainedToSize:CGSizeMake(CELL_CONTENT_WIDTH - (CELL_CONTENT_MARGIN * 2),20000)
                              lineBreakMode:UILineBreakModeWordWrap].height;
        return height + (CELL_CONTENT_MARGIN * 2); 
    }
    if ([indexPath section] == 1) {
        return 80;
    }
    
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    if ([defaults boolForKey:@"autoLetIn"] == NO) {
        if ([indexPath section] == 2)
            return 60;
        if ([indexPath section] == 4)
            return 50;
    } else {
        if ([indexPath section] == 3)
            return 50;
    }
    return 44;
}

/// Стандартный метод задания содержимого строки таблицы, заданной в переменной @a indexPath
/** Для каждой секции мы выделяем отдельную переменную @QRcellID, т.к. качественно все секции
 *  у нас различаются по виду ячеек.
 */
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    /* Нулевая секция - текст подсказки о том, в каком состоянии находится программа:
     * - программа готова готова к сканированию QR-кода, выдается подсказка о том, 
     *   как сканировать код;
     * - программа отсканировала QR-код, билет валиден и помечен как пропущенный;
     * - программа отсканировала QR-код и билен не валиден, выдается сообщение о невалидности 
     *   билета;
     */
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    BOOL autoLetIn = [defaults boolForKey:@"autoLetIn"];
    
    if ([indexPath section] == 0) 
    {
        static NSString *QRcellID0 = @"QRcellID0";
        UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:QRcellID0];
        if (cell == nil) {
            cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:QRcellID0] autorelease];
        }
        cell.textLabel.numberOfLines = 0;
        cell.textLabel.textAlignment = UITextAlignmentCenter;
        cell.userInteractionEnabled = NO;
        cell.textLabel.font = [UIFont boldSystemFontOfSize:FONT_SIZE];
        switch (self.statusCode) {
            case -3:
                cell.textLabel.text = STATUS_MINUS_3;
                cell.textLabel.textColor = [UIColor whiteColor];
                cell.backgroundColor = [UIColor redColor];
                break;
            case -2:
                cell.textLabel.text = STATUS_MINUS_2;
                cell.textLabel.textColor = [UIColor whiteColor];
                cell.backgroundColor = [UIColor redColor];
                break;
            case -1:
                cell.textLabel.text = STATUS_MINUS_1;
                cell.textLabel.textColor = [UIColor whiteColor];
                cell.backgroundColor = [UIColor redColor];
                break;
            case 0:
                cell.textLabel.text = STATUS_0;
                cell.textLabel.textColor = [UIColor blackColor];
                cell.backgroundColor = [UIColor whiteColor];
                break;
            case 1:
                cell.textLabel.text = STATUS_1;
                cell.textLabel.textColor = [UIColor orangeColor];
                cell.backgroundColor = [UIColor whiteColor];
                break;
            case 2:
                cell.textLabel.text = STATUS_2;
                cell.textLabel.textColor = [UIColor whiteColor];
                cell.backgroundColor = [UIColor redColor];
                break;
            case 3:
                if ([defaults boolForKey:@"additionalInfo"] == NO)
                    cell.textLabel.text = STATUS_3;
                else {
                    if ([self.currentTicket.ticketID isEqualToString:@""] == NO) {
                            NSLog(@"tempTicket.ticketID: %@",self.currentTicket.ticketID);
                            NSLog(@"tempTicket.buyerPhone: %@",self.currentTicket.buyerPhone);
                            NSLog(@"tempTicket.purchaseDate: %@",self.currentTicket.purchaseDate);
                            NSString *tempString = [NSString stringWithFormat:@""];
                            if ([self.currentTicket.placeRow isEqualToString:@""] == NO)
                                tempString = [tempString stringByAppendingFormat:@"\nРяд: %@ ",self.currentTicket.placeRow];
                            if ([self.currentTicket.placeColumn isEqualToString:@""] == NO)
                                tempString = [tempString stringByAppendingFormat:@"Место: %@",self.currentTicket.placeColumn];
                            cell.textLabel.text = [NSString stringWithFormat:@"%@. +%@ - %@ %@",STATUS_3,self.currentTicket.buyerPhone,self.currentTicket.purchaseDate,tempString];
                        } else {
                            cell.textLabel.text = STATUS_3;
                        }
                    
                }
                cell.textLabel.textColor = [UIColor whiteColor];
                cell.backgroundColor = [UIColor greenColor];
                break;
            case 4:
                cell.textLabel.text = STATUS_4;
                cell.textLabel.textColor = [UIColor whiteColor];
                cell.backgroundColor = [UIColor redColor];
                break;
            case 5:
                cell.textLabel.text = STATUS_5;
                cell.textLabel.textColor = [UIColor whiteColor];
                cell.backgroundColor = [UIColor redColor];
                break;
            case 6:
                cell.textLabel.text = STATUS_6;
                cell.textLabel.textColor = [UIColor whiteColor];
                cell.backgroundColor = [UIColor redColor];
                break;
                
            default:
                break;
        }
        return cell;
    }    
    
    // Первая секция отвечает за строку, запускающую сканирование QR-кода
    if ([indexPath section] == 1) 
    {
        static NSString *QRcellID1 = @"QRcellID1";
        UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:QRcellID1];
        if (cell == nil) {
                cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:QRcellID1] autorelease];
        }
        cell.textLabel.text = @"  Сканировать QR-код";
        cell.textLabel.font = [UIFont boldSystemFontOfSize:FONT_SIZE + 4];
        cell.imageView.image = [UIImage imageNamed:@"195-barcode.png"];
        return cell;
    }
    
    // Вторая секция отвечает за строку, задающую мероприятие
    if ([indexPath section] == 2) 
    {
        if (autoLetIn == NO) {
            static NSString *QRcellID200 = @"QRcellID200";
            UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:QRcellID200];
            if (cell == nil) {
                cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:QRcellID200] autorelease];
            }
            cell.textLabel.text = [NSString stringWithString:@"   Пропустить"];
            cell.textLabel.font = [UIFont boldSystemFontOfSize:FONT_SIZE + 4];
            cell.imageView.image = [UIImage imageNamed:@"117-todo.png"];
            if ((self.ticketNeedsToBeMarked == YES) && (self.statusCode == 3)){
                cell.userInteractionEnabled = YES;
                cell.textLabel.textColor = [UIColor blackColor];
            } else {
                cell.textLabel.textColor = [UIColor grayColor];
                cell.userInteractionEnabled = NO;
            }
            return cell;
        } else {
            if ([indexPath row] == 0){
                static NSString *QRcellID20 = @"QRcellID20";
                UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:QRcellID20];
                if (cell == nil) {
                    cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:QRcellID20] autorelease];
                }
                cell.textLabel.text = [NSString stringWithFormat:@"%@%d", @"  Пропущено мной:  ", self.ticketsCount];
                cell.textLabel.font = [UIFont boldSystemFontOfSize:FONT_SIZE];
                cell.imageView.image = [UIImage imageNamed:@"111-user.png"];
                cell.userInteractionEnabled = NO;
            
                return cell;
            }
            if ([indexPath row] == 1){
                static NSString *QRcellID21 = @"QRcellID21";
                UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:QRcellID21];
                if (cell == nil) {
                    cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:QRcellID21] autorelease];
                }
                cell.textLabel.text = [NSString stringWithFormat:@"%@%d", @"Пропущено всего: ", self.ticketsTotalCount];
                cell.textLabel.font = [UIFont boldSystemFontOfSize:FONT_SIZE];
                cell.imageView.image = [UIImage imageNamed:@"112-group.png"];
                //cell.userInteractionEnabled = NO;
                return cell;
            }
        }
    }

        
    // Третья секция отвечает за строки, задающие статистику, или за строку, задающую мероприятие
    if ([indexPath section] == 3) 
    {
        if (autoLetIn == NO) {
            if ([indexPath row] == 0){
                static NSString *QRcellID20 = @"QRcellID20";
                UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:QRcellID20];
                if (cell == nil) {
                    cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:QRcellID20] autorelease];
                }
                cell.textLabel.text = [NSString stringWithFormat:@"%@%d", @"  Пропущено мной:  ", self.ticketsCount];
                cell.textLabel.font = [UIFont boldSystemFontOfSize:FONT_SIZE];
                cell.imageView.image = [UIImage imageNamed:@"111-user.png"];
                cell.userInteractionEnabled = NO;
                
                return cell;
            }
            if ([indexPath row] == 1){
                static NSString *QRcellID21 = @"QRcellID21";
                UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:QRcellID21];
                if (cell == nil) {
                    cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:QRcellID21] autorelease];
                }
                cell.textLabel.text = [NSString stringWithFormat:@"%@%d", @"Пропущено всего: ", self.ticketsTotalCount];
                cell.textLabel.font = [UIFont boldSystemFontOfSize:FONT_SIZE];
                cell.imageView.image = [UIImage imageNamed:@"112-group.png"];
                //cell.userInteractionEnabled = NO;
                return cell;
            }
        } else {
            static NSString *QRcellID3 = @"QRcellID3";
            UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:QRcellID3];
            if (cell == nil) {
                cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:QRcellID3] autorelease];
            }
            cell.textLabel.text = @"Задать мероприятие";
            cell.textLabel.font = [UIFont boldSystemFontOfSize:FONT_SIZE];
            cell.imageView.image = [UIImage imageNamed:@"20-gear2.png"];
            return cell;
        }
    }
    
    // Четвертая секция отвечает за строку, задающую мероприятие
    if ([indexPath section] == 4) 
    {
        if (autoLetIn == NO) {
            static NSString *QRcellID3 = @"QRcellID3";
            UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:QRcellID3];
            if (cell == nil) {
                cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:QRcellID3] autorelease];
            }
            cell.textLabel.text = @"Задать мероприятие";
            cell.textLabel.font = [UIFont boldSystemFontOfSize:FONT_SIZE];
            cell.imageView.image = [UIImage imageNamed:@"20-gear2.png"];
            return cell;
        }
    }
    return nil;
}

/// Стандартный метод обработки нажатия на строку таблицы, заданную в переменной @a indexPath
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
    // Код обработки нажатия на строку, отвечающую за сканирование QR-кода.
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    BOOL autoLetIn = [defaults boolForKey:@"autoLetIn"];
    
    if ([indexPath section] == 1)
    {
        if ([self.ourEventName isEqualToString:@""]) {
            [self showAlertWithTitle:@"Внимание!" withMessage:@"Перед началом сканирования билетов задайте мероприятие!" withButton:@"ОК"];
        } else {
            self.scanningTicket = YES;
            [self scanButtonTapped];
        }
    }
    
    if ([indexPath section] == 2) {
        if (autoLetIn == NO) {
            // TODO: Логика отправки на сервер данных о том, что билет пропущен 
            NSString *httpRequestAddressPart3 = [NSString stringWithFormat:@"%@", [self.qrText substringWithRange:NSMakeRange(QR_TICKET_API_LENGTH, 24)]];
            NSLog(@"Ticket code = %@",self.qrText);
            
            // Составляем строку запроса, с учетом кода билета и кода мероприятия
            NSString *requestString = [NSString stringWithFormat:@"%@%@%@%@%@%@", HTTP_REQUEST_ADDRESS_PART1, HTTP_REQUEST_ADDRESS_PART2, httpRequestAddressPart3, HTTP_REQUEST_ADDRESS_PART4, self.ourEventCode,HTTP_REQUEST_MARK_TICKET];
            NSLog(@"Request to server string = %@",requestString);
            
            // Обращаемся к серверу и берем у него ответ о валидности билета
            NSString *serverResponseString = [[NSString alloc] init];
            serverResponseString = [dataFetcher grabURL:requestString];
            NSLog(@"Server response = %@",serverResponseString);
            
            int returnCode;
            if (serverResponseString == nil)
                returnCode = - 2;
            else
                if ([[NSScanner scannerWithString:serverResponseString] scanInt:nil])
                    returnCode = [serverResponseString intValue];
                else 
                    returnCode = -1;
            
            if ((self.ticketNeedsToBeMarked == YES) && (returnCode == 1)) {
                // Увеличиваем количество прошедших на 1
                self.ticketsCount = self.ticketsCount + 1;
                // Получаем от сервера общее количество прошедших билетов
                [self getTotalMarkedTickets];
                // Запоминаем данные о билете в массиве билетов
                [self saveTicketWithURL:self.qrText withID:httpRequestAddressPart3 withEventID:self.ourEventCode withStatus:returnCode withServerResponse:serverResponseString];
                // Делаем уведомление о том, что отсканировали еще один билет, 
                // чтобы обновить список отсканированных билетов
                [[NSNotificationCenter defaultCenter] postNotificationName: @"ticketScanned" object: nil];
                self.ticketNeedsToBeMarked = NO;
                [mainTable reloadData];
            }
            
            // Запоминаем данные о билете во временной переменной
            [self saveCurrentTicketWithURL:self.qrText withID:httpRequestAddressPart3 withEventID:self.ourEventCode withStatus:returnCode withServerResponse:serverResponseString];
            
            if (returnCode == 1)
            {
                [self showAlertWithTitle:@"Внимание!" withMessage:@"Билет пропущен!" withButton:@"ОК"];
            } else {
                [self showAlertWithTitle:@"Внимание!" withMessage:@"При попытке пропустить билет возникла неизвестная ошибка!" withButton:@"ОК"];
            }
            
        } else {
            if ([indexPath row] == 1) {
                // Получаем от сервера общее количество пропущенных на мероприятие билетов
                [self getTotalMarkedTickets];
            }
        }
    }
    
    // Код обработки нажатия на строку, отвечающую за сканирование QR-кода Мероприятия.
    if ([indexPath section] == 3)
    {
        if (autoLetIn == NO) {
            if ([indexPath row] == 1) {
                // Получаем от сервера общее количество пропущенных на мероприятие билетов
                [self getTotalMarkedTickets];
            }
        } else {
            UIActionSheet *actionSheetFilterActive = [[UIActionSheet alloc] initWithTitle:@"Вы хотите задать мероприятие?" delegate:self cancelButtonTitle:@"Нет" destructiveButtonTitle:@"Да" otherButtonTitles: nil];
            [actionSheetFilterActive showInView:[self.view window]];
            [actionSheetFilterActive release];
        }
    }
    
    // Код обработки нажатия на строку, отвечающую за сканирование QR-кода Мероприятия.
    if ([indexPath section] == 4)
    {
        if (autoLetIn == NO) {
            UIActionSheet *actionSheetFilterActive = [[UIActionSheet alloc] initWithTitle:@"Вы хотите задать мероприятие?" delegate:self cancelButtonTitle:@"Нет" destructiveButtonTitle:@"Да" otherButtonTitles: nil];
            [actionSheetFilterActive showInView:[self.view window]];
            [actionSheetFilterActive release];
        }
    }

    // Убираем выделение строки после нажатия на строку для красоты
    [tableView deselectRowAtIndexPath:indexPath animated:YES];
}

#pragma mark - Scanning Methods
/// Метод сканирование QR-кода.
/** В методе сканирования QR-кода создается объект класса ZBarReaderViewController, 
 *  который управляет интерфейсом фотографирования QR-кода, а также содержит методы 
 *  распознавания сфотографированного QR-кода. Для ускорения и повышения качества 
 *  распознавания QR-кодов, мы отключаем другие доступные для распознавания в библиотеке 
 *  ZBarSDK виды штрих-кодов при помощи метода setSymbology:config:to:, последовательно 
 *  отключая все доступные штрих-коды, а затем включая QR-коды.
 *  После этого выводим интерфейс фотографирования QR-кода в модальном окне.
 */
- (void)scanButtonTapped{
    // Создаем объект, получающий картинку с камеры
    ZBarReaderViewController *reader = [ZBarReaderViewController new];
    reader.readerDelegate = self;
    reader.supportedOrientationsMask = ZBarOrientationMaskAll;
    
    ZBarImageScanner *scanner = reader.scanner;
    
    // Мы будем считывать только QR-коды
    [scanner setSymbology:0 config:ZBAR_CFG_ENABLE to:0];
    [scanner setSymbology:ZBAR_QRCODE config:ZBAR_CFG_ENABLE to:1];
    reader.readerView.zoom = 1.0;
    
    [self presentModalViewController:reader animated:YES];
    [reader release];
}

/// Метод обработки результатов сканирования QR-кода.
/** В методе обработки результатов сканирования QR-кода мы извлекаем полученные из QR-кода 
 *  данные из переменной @a symbol, и сохраняем текст QR-кода в переменную @a qrText, 
 *  После этого, перерисовываем таблицу для отображения полученных данных на экране приложения.
 */
- (void) imagePickerController:(UIImagePickerController *)picker didFinishPickingMediaWithInfo:(NSDictionary *)info {
    NSLog(@"self.scanNextTicket = %d",self.scanNextTicket);
    if (self.scanNextTicket == NO)
        return;
    
    
    id<NSFastEnumeration> results = [info objectForKey:ZBarReaderControllerResults];
    ZBarSymbol *symbol = nil;
    
    for(symbol in results) 
        // EXAMPLE: just grab the first barcode
        break;
    
    // Запоминаем распознанные из QR-кода данные
    self.qrText = symbol.data;
    NSLog(@"QR-code text = %@",self.qrText);
    
    // ADD: dismiss the controller (NB dismiss from the *reader*!)
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    if (([defaults boolForKey:@"scanByButton"] == YES) || (self.scanningTicket == NO))
        [picker dismissModalViewControllerAnimated:YES];
    
    // Проверка на то, сканируем ли мы сейчас билеты или мероприятия
    if (self.scanningTicket == YES) {
        
        if ([defaults boolForKey:@"scanByButton"] == NO)
            self.scanNextTicket = NO;
        else
            self.scanNextTicket = YES;
        // Проверяем валидный ли билет
        BOOL ticketIsProper = NO;
        if (self.qrText.length >= QR_TICKET_API_LENGTH)
            if ([[self.qrText substringWithRange:NSMakeRange(0, QR_TICKET_API_LENGTH)] isEqualToString:HTTP_REQUEST_ADDRESS_TICKET_VIEW] == YES)
                ticketIsProper = YES;
        
        if (ticketIsProper == YES) {
            // Идем на сервер за данными о валидности билета
            // Извлекаем из распознанного QR-кода идентификатор билета
            NSString *httpRequestAddressPart3 = [NSString stringWithFormat:@"%@", [self.qrText substringWithRange:NSMakeRange(QR_TICKET_API_LENGTH, 24)]];
            NSLog(@"Ticket code 2 = %@",httpRequestAddressPart3);
        
            NSString *requestString = [NSString string];
            // Составляем строку запроса, с учетом кода билета и кода мероприятия
            NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
            if ([defaults boolForKey:@"autoLetIn"] == YES)
                self.ticketNeedsToBeMarked = NO;
            else 
                self.ticketNeedsToBeMarked = YES;
            if ([defaults boolForKey:@"scanByButton"] == NO)
                self.ticketNeedsToBeMarked = NO;
            
            if (self.ticketNeedsToBeMarked == YES)
                requestString = [NSString stringWithFormat:@"%@%@%@%@%@%@", HTTP_REQUEST_ADDRESS_PART1, HTTP_REQUEST_ADDRESS_PART2, httpRequestAddressPart3, HTTP_REQUEST_ADDRESS_PART4, self.ourEventCode,HTTP_REQUEST_DONT_MARK_TICKET];
            else 
                requestString = [NSString stringWithFormat:@"%@%@%@%@%@%@", HTTP_REQUEST_ADDRESS_PART1, HTTP_REQUEST_ADDRESS_PART2, httpRequestAddressPart3, HTTP_REQUEST_ADDRESS_PART4, self.ourEventCode,HTTP_REQUEST_MARK_TICKET];
            NSLog(@"Server request string = %@",requestString);
        
            // Обращаемся к серверу и берем у него ответ о валидности билета
            NSString *serverResponseString = [[NSString alloc] init];
            serverResponseString = [dataFetcher grabURL:requestString];
            NSLog(@"Server response string = %@",serverResponseString);
        
            int returnCode;
            if (serverResponseString == nil)
                returnCode = - 2;
            else
                if ([[NSScanner scannerWithString:serverResponseString] scanInt:nil])
                    returnCode = [serverResponseString intValue];
                else 
                    returnCode = -1;
        
            switch (returnCode) {
                case -2:    [self setStatusCodeWithAlert:-2];   break;
                case -1:    [self setStatusCodeWithAlert:-1];   break;
                case 0:     [self setStatusCodeWithAlert:2];    break;
                case 1:
                    // Билет валидный и его можно пропускать
                    self.statusCode = 3;
                    
                    if (self.ticketNeedsToBeMarked == NO) {
                        // Запоминаем данные о билете в массиве билетов
                        [self saveTicketWithURL:self.qrText withID:httpRequestAddressPart3 withEventID:self.ourEventCode withStatus:returnCode withServerResponse:serverResponseString];
                        // Увеличиваем количество прошедших на 1
                        self.ticketsCount = self.ticketsCount + 1;
                        // Получаем от сервера общее количество прошедших билетов
                        [self getTotalMarkedTickets];
                        // Делаем уведомление о том, что отсканировали еще один билет, 
                        // чтобы обновить список отсканированных билетов
                        [[NSNotificationCenter defaultCenter] postNotificationName: @"ticketScanned" object: nil];
                    }
                    // Запоминаем данные о билете во временной переменной
                    [self saveCurrentTicketWithURL:self.qrText withID:httpRequestAddressPart3 withEventID:self.ourEventCode withStatus:returnCode withServerResponse:serverResponseString];
                    
                    [self setStatusCodeWithAlert:3];
                    break;
                    
                case 2:     [self setStatusCodeWithAlert:4];    break;
                case 3:     [self setStatusCodeWithAlert:5];    break;
                case 4:     [self setStatusCodeWithAlert:6];    break;
                default:    break;
             }
        } else {
            // Формат QR-кода не соответствует формату билета
            [self showAlertWithTitle:@"Внимание!" withMessage:@"Указанный Вами QR-код не является кодом билета!" withButton:@"ОК"];
        }
    } else {
        // Случай сканирования QR-кода мероприятия
        // Проверяем, является ли отсканированный QR-код валидным кодом мероприятия
        BOOL eventCodeIsProper = NO;
        if (self.qrText.length == QR_EVENT_LENGTH)
            if ([[self.qrText substringWithRange:NSMakeRange(0, QR_EVENT_API_LENGTH)] isEqualToString:@"apikey:"] == YES)
                eventCodeIsProper = YES;
        
        // Если код задает мероприятие, работаем дальше, если нет - выдаем сообщение об ошибке
        if (eventCodeIsProper == YES) {
            // Сохраняем в переменной ourEventCode код мероприятия
            self.ourEventCode = [self.qrText substringWithRange:NSMakeRange(QR_EVENT_API_LENGTH, QR_EVENT_LENGTH - QR_EVENT_API_LENGTH)];
            NSLog(@"Event code = %@",self.ourEventCode);

            // Идем на сервер за данными о мероприятии
            // Составляем строку запроса, с учетом кода билета и кода мероприятия
            NSString *requestString = [NSString stringWithFormat:@"%@%@%@%@", HTTP_REQUEST_ADDRESS_PART1, HTTP_REQUEST_GET_EVENT_PART1, self.ourEventCode, HTTP_REQUEST_GET_EVENT_PART2];
            NSLog(@"Request string to server with event code = %@",requestString);
            
            // Обращаемся к серверу и берем у него ответ о валидности билета
            NSString *serverResponseString = [[NSString alloc] init];
            serverResponseString = [dataFetcher grabURL:requestString];
            NSLog(@"Server response about event code: %@",serverResponseString);
            
            // Проверяем, есть ли соединение с сервером
            if (serverResponseString != nil)
            {
                // Сервер что-то вернул, значит соединение с сервером есть
                if ([serverResponseString isEqualToString:@"Data is not correct"] == NO) {
                    // Сервер вернул валидное название мероприятия
                    self.ourEventName = serverResponseString;
                    self.statusCode = 1;
                    self.ticketsTotalCount = 0;
                    self.ticketsCount = 0;
                } else {
                    // Сервер не смог вернуть название мероприятия по причине отсутствия такого мероприятия в базе
                    self.ourEventName = @"";
                    self.statusCode = -3;
                }
            } else {
                // Соединения с сервером нет, либо по причине отсутствия интернета, либо по причине некорректной работы сервера
                self.statusCode = -2;
            }
        } else {
            // Отсканированный код мероприятия оказался невалидным
            self.statusCode = 0;
            [self showAlertWithTitle:@"Внимание!" withMessage:@"Указанный Вами QR-код не является кодом мероприятия!" withButton:@"ОК"];
        }
    }

    [mainTable reloadData];
}

#pragma mark - Support Methods

/// Метод сохранения данных о пропущенном билете в списке пропущенных билетов
- (void)saveTicketWithURL:(NSString *)ticketURL withID:(NSString *)ticketID withEventID:(NSString *)eventID withStatus:(NSInteger)returnCode withServerResponse:(NSString *)serverResponseString {
    // Запоминаем данные о билете в массиве билетов
    QR_ScannerAppDelegate *mainDelegate = (QR_ScannerAppDelegate *)[[UIApplication sharedApplication] delegate];
    Ticket *tempTicket = [[Ticket alloc] init];
    tempTicket.ticketURL = ticketURL;
    tempTicket.ticketID = ticketID;
    tempTicket.eventID = eventID;
    tempTicket.ticketStatus = returnCode;
    
    NSArray *ticketItems = [serverResponseString componentsSeparatedByString:@";"];
    if ([ticketItems count] >=2)
        tempTicket.buyerPhone = [ticketItems objectAtIndex:1];
    if ([ticketItems count] >= 3)
        tempTicket.purchaseDate = [ticketItems objectAtIndex:2];
    if ([ticketItems count] >= 4)
        tempTicket.placeRow = [ticketItems objectAtIndex:3];
    if ([ticketItems count] >= 5)
        tempTicket.placeColumn = [ticketItems objectAtIndex:4];
    
    for (int i = 0; i < [ticketItems count]; i++) {
        NSLog(@"saveTicketWithURL - Item %d = %@", i, [ticketItems objectAtIndex:i]);
    }
    
    [mainDelegate.tickets addObject:tempTicket];
}

/// Метод сохранения данных об отсканированном, но еще не пропущенном билете
- (void)saveCurrentTicketWithURL:(NSString *)ticketURL withID:(NSString *)ticketID withEventID:(NSString *)eventID withStatus:(NSInteger)returnCode withServerResponse:(NSString *)serverResponseString {
    // Сперва чистим текущий билет
    [self clearCurrentTicket];
    // Запоминаем данные о билете в массиве билетов
    self.currentTicket.ticketURL = ticketURL;
    self.currentTicket.ticketID = ticketID;
    self.currentTicket.eventID = eventID;
    self.currentTicket.ticketStatus = returnCode;
    NSArray *ticketItems = [serverResponseString componentsSeparatedByString:@";"];
    if ([ticketItems count] >=2)
        self.currentTicket.buyerPhone = [ticketItems objectAtIndex:1];
    if ([ticketItems count] >= 3)
        self.currentTicket.purchaseDate = [ticketItems objectAtIndex:2];
    if ([ticketItems count] >= 4)
        self.currentTicket.placeRow = [ticketItems objectAtIndex:3];
    if ([ticketItems count] >= 5)
        self.currentTicket.placeColumn = [ticketItems objectAtIndex:4];
    
    for (int i = 0; i < [ticketItems count]; i++) {
        NSLog(@"saveCurrentTicketWithURL - Item %d = %@", i, [ticketItems objectAtIndex:i]);
    }
}

/// Метод очистки данных текущего билета
- (void)clearCurrentTicket {
    self.currentTicket.ticketURL = @"";
    self.currentTicket.ticketID = @"";
    self.currentTicket.eventID = @"";
    self.currentTicket.ticketStatus = -100;
    self.currentTicket.buyerPhone = @"";
    self.currentTicket.purchaseDate = @"";
    self.currentTicket.placeRow = @"";
    self.currentTicket.placeColumn = @"";
}

/// Метод устанавливает код состояния программы для подсказки в первой строке и выдает alert-сообщение в режиме непрерывного сканирования
- (void)setStatusCodeWithAlert:(NSInteger)code {
    self.statusCode = code;
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    if ([defaults boolForKey:@"scanByButton"] == NO) {
        NSString *title = [NSString string];
        if (code == 3)
            title = @"Билет пропущен!";
        else
            title = @"Внимание!";
        NSString *message = [NSString string];
        switch (code) {
            case -3: message = STATUS_MINUS_3; break;
            case -2: message = STATUS_MINUS_2; break;
            case -1: message = STATUS_MINUS_1; break;
            case 0:  message = STATUS_0; break;
            case 1:  message = STATUS_1; break;
            case 2:  message = STATUS_2; break;
            case 3:  
                if ([defaults boolForKey:@"additionalInfo"] == NO)
                    message = STATUS_3;
                else {
                    if ([self.currentTicket.ticketID isEqualToString:@""] == NO) {
                        NSLog(@"message: %@",self.currentTicket.ticketID);
                        NSLog(@"message: %@",self.currentTicket.buyerPhone);
                        NSLog(@"message: %@",self.currentTicket.purchaseDate);
                        NSString *tempString = [NSString stringWithFormat:@""];
                        if ([self.currentTicket.placeRow isEqualToString:@""] == NO)
                            tempString = [tempString stringByAppendingFormat:@"\nРяд: %@ ",self.currentTicket.placeRow];
                        if ([self.currentTicket.placeColumn isEqualToString:@""] == NO)
                            tempString = [tempString stringByAppendingFormat:@"Место: %@",self.currentTicket.placeColumn];
                        
                        message = [NSString stringWithFormat:@"%@.\n Телефон покупателя: +%@ \n Дата покупки: %@ %@",STATUS_3,self.currentTicket.buyerPhone,self.currentTicket.purchaseDate,tempString];
                    } else {
                        message = STATUS_3;
                        NSLog(@"message: %@",self.currentTicket.ticketID);
                    }
                    
                }

                break;
            case 4:  message = STATUS_4; break;
            case 5:  message = STATUS_5; break;
            case 6:  message = STATUS_6; break;
            default: break;
        }
        [self showAlertWithTitle:title withMessage:message withButton:@"ОК"];
    }
}

/// Метод показывает alertview с заданными параметрами
- (void)showAlertWithTitle:(NSString *)title withMessage:(NSString *)message withButton:(NSString *)button {
    UIAlertView *alert = [[UIAlertView alloc] initWithTitle:title message:message delegate:self cancelButtonTitle:button otherButtonTitles: nil];
    [alert show];
    [alert release];
}

/// Метод обработки нажатия кнопки ОК alertview для контроля сканирования многоразовых билетов
- (void)alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex
{
    if (buttonIndex == 0)
    {
        // Пока пользователь не нажмет кнопку ОК, сканировать дальше не будем
        self.scanNextTicket = YES;
    }
}

/** Метод получает от сервера общее количество пропущенных на мероприятие билетов и сохраняет его
 *  в глобальной переменной @a ticketsTotalCount. Структура запроса к серверу выглядит так:
 *  http://uat.showcode.ru/events/webApi?event=идентификатор_мероприятия&count=1
 */
- (void)getTotalMarkedTickets {
    // Составляем строку запроса количества пропущенных билетов, с учетом кода мероприятия
    NSString *requestString = [NSString stringWithFormat:@"%@%@%@%@", HTTP_REQUEST_ADDRESS_PART1, HTTP_REQUEST_GET_EVENT_PART1,self.ourEventCode,HTTP_REQUEST_GET_EVENT_PART3];
    NSLog(@"Server request string = %@",requestString);
    
    // Обращаемся к серверу и берем у него ответ о валидности билета
    NSString *serverResponseString = [[NSString alloc] init];
    serverResponseString = [dataFetcher grabURL:requestString];
    NSLog(@"Server response string = %@",serverResponseString);
    
    int returnCode;
    if (serverResponseString == nil)
        returnCode = - 2;
    else
        if ([[NSScanner scannerWithString:serverResponseString] scanInt:nil])
            returnCode = [serverResponseString intValue];
        else 
            returnCode = -1;
    if (returnCode == -2) {
        [self setStatusCodeWithAlert:-2];
    } else {
        if (returnCode == -1) {
            [self setStatusCodeWithAlert:-1];
        } else {
            self.ticketsTotalCount = returnCode;
        }
    }
    [mainTable reloadData];
}

#pragma mark - Saving Settings Methods
/// Функция поиска имени файла настроек в файловой системе устройства, возвращает путь к файлу настроек
- (NSString *)dataFilePath {
    NSArray *paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES);
    NSString *documentsDirectory = [paths objectAtIndex:0];
    return [documentsDirectory stringByAppendingPathComponent:kFileName];
}

/// Функция сохранения настроек мероприятия перед завершением приложения
- (void)applicationWillResignActive:(NSNotification *)notification{
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    if ([defaults boolForKey:@"saveEventData"] == YES) {
        NSMutableArray *array = [[NSMutableArray alloc] init];
        [array addObject:self.ourEventCode];
        [array addObject:self.ourEventName];
        NSLog(@"self.ourEventCode: %@",self.ourEventCode);
        NSLog(@"self.ourEventName: %@",self.ourEventName);
        [array writeToFile:[self dataFilePath] atomically:YES];
        [array release];
    } else {
        NSMutableArray *array = [[NSMutableArray alloc] init];
        [array addObject:@""];
        [array addObject:@""];
        [array writeToFile:[self dataFilePath] atomically:YES];
        [array release];
    }
}

#pragma mark - View lifecycle

/// Стандартная функция настройки представления после его загрузки, здесь инициализируем переменные.
- (void)viewDidLoad
{
    
    self.qrText = [[NSString alloc] init];
    self.dataFetcher = [[DataFetcher alloc] init];
    self.statusCode = 0;
    self.ourEventName = [[NSString alloc] initWithString:@""];
    self.ourEventCode = [[NSString alloc] initWithString:@""];
    self.continuosScan = YES;
    self.ticketsCount = 0;
    self.ticketsTotalCount = 0;
    self.ticketNeedsToBeMarked = NO;
    self.currentTicket = [[Ticket alloc] init];
    self.scanNextTicket = YES;
    
    // Подписываемся на сообщение о том, что надо сканировать следующий код
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(scanNextQR)name:@"scanFinished" object:nil];
    
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    if ([defaults boolForKey:@"saveEventData"] == YES) {
        // Читаем из файла настроек код и название мероприятия
        NSString *filePath = [self dataFilePath];
        if ([[NSFileManager defaultManager] fileExistsAtPath:filePath]) {
            NSArray *array = [[NSArray alloc] initWithContentsOfFile:filePath];
            self.ourEventCode = [array objectAtIndex:0];
            self.ourEventName = [array objectAtIndex:1];
            [array release];
        }
    }
    
    UIApplication *app = [UIApplication sharedApplication];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(applicationWillResignActive:) name:UIApplicationWillResignActiveNotification object:app];
    
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(settingsChanged) name:@"settingsChanged" object:nil];
    
    [super viewDidLoad];
}

/// Метод обновляет вид табличного представления после обновления настроек
- (void)settingsChanged {
    [self.mainTable reloadData];
    NSLog(@"Data reloaded because of settings update");
}

/// Стандартная функция освобождения памяти при выгрузке из памяти представления.
- (void)viewDidUnload
{
    [super viewDidUnload];
    // Release any retained subviews of the main view.
    // e.g. self.myOutlet = nil;
    // Освобождаем следящего за сообщением о том, что надо сканировать следующий код
    [[NSNotificationCenter defaultCenter] removeObserver:self name:@"scanFinished" object:nil];
    [[NSNotificationCenter defaultCenter] removeObserver:self name:@"settingsChanged" object:nil];
}

/// Стандартная функция поддержки поворота экрана при повороте устройства.
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
    // Return YES for supported orientations
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}

/// Стандартная функция освобождения памяти при удалении представления.
- (void) dealloc {
    [self.qrText release];
    [self.dataFetcher release];
    [self.ourEventName release];
    [self.ourEventCode release];
    [self.currentTicket release];
    [super dealloc];
}

/// Стандартная функция очистки памяти при получении сигнала от операционной системы о нехватке памяти.
- (void)didReceiveMemoryWarning
{
    // Releases the view if it doesn't have a superview.
    [super didReceiveMemoryWarning];
}

@end