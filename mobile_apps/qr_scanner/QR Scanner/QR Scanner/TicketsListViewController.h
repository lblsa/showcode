//
//  TicketsListViewController.h
//  QR Scanner
//
//  Created by Apple Developer on 22.12.11.
//  Copyright (c) 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>

@class Ticket;

@interface TicketsListViewController : UIViewController <UITableViewDelegate, UITableViewDataSource> {
    Ticket *ticket;
    NSMutableArray *tickets;
    UILabel *noTicketsLabel;
    UITableView *ticketListTable;
}

@property (nonatomic, retain) Ticket *ticket;
@property (nonatomic, retain) NSMutableArray *tickets;
@property (nonatomic, retain) IBOutlet UILabel *noTicketLabel;
@property (nonatomic, retain) IBOutlet UITableView *ticketListTable;

@end
