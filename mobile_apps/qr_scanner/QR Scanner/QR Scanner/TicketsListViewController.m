//
//  TicketsListViewController.m
//  QR Scanner
//
//  Created by Apple Developer on 22.12.11.
//  Copyright (c) 2011 __MyCompanyName__. All rights reserved.
//

#import "TicketsListViewController.h"
#import "Ticket.h"
#import "QR_ScannerAppDelegate.h"
#import "TicketDetailsViewController.h"

#define FONT_SIZE 16.0f                 /// < Размер шрифта ячейки
#define CELL_CONTENT_WIDTH 320.0f       /// < Ширина ячейки 
#define CELL_CONTENT_MARGIN 14.0f       /// < Величина отступа содержимого таблицы от родительского элемента


@implementation TicketsListViewController

@synthesize ticket;
@synthesize tickets;
@synthesize noTicketLabel;
@synthesize ticketListTable;

#pragma mark - TableViewDelegate Methods
/// Стандартный метод задания заголовков секций таблицы, секции начинаются с 0.
- (NSString *)tableView:(UITableView *)tv titleForHeaderInSection:(NSInteger)section {
    return @"";
}
/// Стандартный метод задания нижних подписей секций таблицы, секции начинаются с 0.
- (NSString *)tableView:(UITableView *)tv titleForFooterInSection:(NSInteger)section { 
    return @"";
}

/// Стандартный метод задания количества секций таблицы
- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView { 
    return 1;
}

/// Стандартный метод задания количества ячеек в секции таблицы, определяемой переменной @a section
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section { 
    QR_ScannerAppDelegate *mainDelegate = (QR_ScannerAppDelegate *)[[UIApplication sharedApplication] delegate];
    return [mainDelegate.tickets count];
}

/// Стандартный метод задания высоты строки таблицы, заданной в переменной @a indexPath
- (CGFloat) tableView: (UITableView *) tableView heightForRowAtIndexPath: (NSIndexPath *) indexPath {
    return 44;
}

/// Стандартный метод задания содержимого строки таблицы, заданной в переменной @a indexPath
/** Для каждой секции мы выделяем отдельную переменную @QRcellID, т.к. качественно все 3 секции
 *  у нас различаются по виду ячеек.
 */
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    /** 
     *
     *
     */
    
    static NSString *TicketCell = @"TicketCell";
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:TicketCell];
    if (cell == nil) {
        cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:TicketCell] autorelease];
    }
    QR_ScannerAppDelegate *mainDelegate = (QR_ScannerAppDelegate *)[[UIApplication sharedApplication] delegate];
    Ticket *tempTicket = [[Ticket alloc] init];
    NSUInteger ticketsCount = [mainDelegate.tickets count];
    tempTicket = (Ticket *)[mainDelegate.tickets objectAtIndex:ticketsCount - [indexPath row] - 1];
    
    cell.textLabel.text = [NSString stringWithFormat:@"#%d  +%@(%@)%@-%@-%@  %@", 
                           ticketsCount - [indexPath row],
                           [tempTicket.buyerPhone substringWithRange:NSMakeRange(0, 1)],
                           [tempTicket.buyerPhone substringWithRange:NSMakeRange(1, 3)],
                           [tempTicket.buyerPhone substringWithRange:NSMakeRange(4, 3)],
                           [tempTicket.buyerPhone substringWithRange:NSMakeRange(7, 2)],
                           [tempTicket.buyerPhone substringWithRange:NSMakeRange(9, 2)],
                           [tempTicket.purchaseDate substringWithRange:NSMakeRange(0, 10)]];
    cell.textLabel.textAlignment = UITextAlignmentLeft;
    cell.textLabel.font = [UIFont boldSystemFontOfSize:FONT_SIZE];
    cell.accessoryType = UITableViewCellAccessoryDisclosureIndicator;
    //[tempTicket release];
    return cell;
}

/// Стандартный метод обработки нажатия на строку таблицы, заданную в переменной @a indexPath
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
    /* */
    QR_ScannerAppDelegate *mainDelegate = (QR_ScannerAppDelegate *)[[UIApplication sharedApplication] delegate];
    Ticket *tempTicket = [[Ticket alloc] init];
    NSUInteger ticketsCount = [mainDelegate.tickets count];
    tempTicket = (Ticket *)[mainDelegate.tickets objectAtIndex:ticketsCount - [indexPath row] - 1];
    
    TicketDetailsViewController *ticketDetailsVC = [[TicketDetailsViewController alloc] initWithNibName:@"TicketDetails" bundle:nil];
    ticketDetailsVC.title = @"Данные по билету";
    ticketDetailsVC.ticketDetailsURL = tempTicket.ticketURL;
    [self.navigationController pushViewController:ticketDetailsVC animated:YES];
    
    // Убираем выделение строки после нажатия на строку для красоты
    [tableView deselectRowAtIndexPath:indexPath animated:YES];
    
    //[tempTicket release];
}

#pragma mark - View lifecycle

- (void)ticketScannedLogic {
    [ticketListTable reloadData];
    [self.noTicketLabel setHidden:YES];
    [self.ticketListTable setHidden:NO];

}

// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad
{
    [super viewDidLoad];
    [self.noTicketLabel setHidden:YES];
    [self.ticketListTable setHidden:NO];
    
    // Подписываемся на сообщение о том, что надо отсканирован очередной билет
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(ticketScannedLogic)name:@"ticketScanned" object:nil];
    
    // Меняем название кнопки Назад
    self.navigationItem.backBarButtonItem =
    [[[UIBarButtonItem alloc] initWithTitle:@"Назад" style:UIBarButtonItemStyleBordered target:nil action:nil] autorelease];
}

- (void)viewDidUnload
{
    [super viewDidUnload];
    // Release any retained subviews of the main view.
    // e.g. self.myOutlet = nil;
    
    // Освобождаем следящего за сообщением о том, что надо сканировать следующий код
    [[NSNotificationCenter defaultCenter] removeObserver:self name:@"ticketScanned" object:nil];
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
    // Return YES for supported orientations
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}

- (void)didReceiveMemoryWarning
{
    // Releases the view if it doesn't have a superview.
    [super didReceiveMemoryWarning];
    
    // Release any cached data, images, etc that aren't in use.
}

@end
