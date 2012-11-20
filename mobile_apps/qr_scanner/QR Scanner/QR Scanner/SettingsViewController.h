//
//  SettingsViewController.h
//  QR Scanner
//
//  Created by Apple Developer on 22.12.11.
//  Copyright (c) 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface SettingsViewController : UIViewController <UITableViewDelegate, UITableViewDataSource> {
    UITableView *settingsTable;
    UISwitch *autoLetIn;
    UISwitch *scanByButton;
    UISwitch *additionalInfo;
    UISwitch *saveEventData;
}

@property (nonatomic, retain) IBOutlet UITableView *settingsTable;
@property (nonatomic, retain) IBOutlet UISwitch *autoLetIn;
@property (nonatomic, retain) IBOutlet UISwitch *scanByButton;
@property (nonatomic, retain) IBOutlet UISwitch *additionalInfo;
@property (nonatomic, retain) IBOutlet UISwitch *saveEventData;

@end
