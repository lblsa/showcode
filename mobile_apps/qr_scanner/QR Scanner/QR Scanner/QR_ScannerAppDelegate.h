/** @file QR_ScannerAppDelegate.h
 *  @brief Заголовочный файл делегата приложения. Создан 14.10.11
 *  
 *  Заголовочный файл класса-делегата приложения, создает окно приложения и 
 *  вызывает ViewController, управляющий основным экраном приложения.
 */

#import <UIKit/UIKit.h>

//@class QR_ScannerViewController;
@class MainLogicViewController;
@class Ticket;

//MyAppDelegate *mainDelegate = (MyAppDelegate *)[[UIApplication sharedApplication] delegate];

@interface QR_ScannerAppDelegate : NSObject <UIApplicationDelegate> {
    UITabBarController *tabbarController;
    Ticket *singleTicket;
    NSMutableArray *tickets;
    BOOL autoLetIn;
    BOOL scanByButton;
    BOOL additionalInfo;
    BOOL saveEventData;
}

@property (nonatomic, strong) IBOutlet UIWindow *window;

@property (nonatomic, retain) IBOutlet MainLogicViewController *viewController;
@property (nonatomic, retain) IBOutlet UITabBarController *tabbarController;
@property (nonatomic, retain) Ticket *singleTicket;
@property (nonatomic, retain) NSMutableArray *tickets;
@property (nonatomic) BOOL autoLetIn;
@property (nonatomic) BOOL scanByButton;
@property (nonatomic) BOOL additionalInfo;
@property (nonatomic) BOOL saveEventData;



@end
