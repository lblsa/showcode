//
//  TicketDetailsViewController.h
//  QR Scanner
//
//  Created by Apple Developer on 22.12.11.
//  Copyright (c) 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface TicketDetailsViewController : UIViewController {
    NSString *ticketDetailsURL;
    UIWebView *ticketDetails;
}

@property (nonatomic, copy) NSString *ticketDetailsURL;
@property (nonatomic, retain) IBOutlet UIWebView *ticketDetails;

@end
