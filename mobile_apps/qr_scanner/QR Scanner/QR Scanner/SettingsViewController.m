//
//  SettingsViewController.m
//  QR Scanner
//
//  Created by Apple Developer on 22.12.11.
//  Copyright (c) 2011 __MyCompanyName__. All rights reserved.
//

#import "SettingsViewController.h"
#import "QR_ScannerAppDelegate.h"

#define FONT_SIZE_FOOTER 12.0f          /// < Размер шрифта подписи под ячейкой
#define FONT_SIZE 14.0f                 /// < Размер шрифта ячейки
#define CELL_CONTENT_WIDTH 320.0f       /// < Ширина ячейки 
#define CELL_CONTENT_MARGIN 14.0f       /// < Величина отступа содержимого таблицы от родительского элемента

#define SETTINGS_TEXT_PART0 @"При включении билеты автоматически будут помечаться, как пройденные."
#define SETTINGS_TEXT_PART1 @"При включении для сканирования необходимо будет нажимать кнопку \"Сканировать QR-код\"."
#define SETTINGS_TEXT_PART2 @"При включении будет выдаваться расширенная информация по билету (места, прочие параметры)."
#define SETTINGS_TEXT_PART3 @"При включении текущее заданное мероприятие будет сохраняться после выключения приложения."
#define SETTINGS_LABEL0 @"Помечать автоматически"
#define SETTINGS_LABEL1 @"Сканировать по кнопке"
#define SETTINGS_LABEL2 @"Расширенная информация"
#define SETTINGS_LABEL3 @"Запоминать мероприятие"

@implementation SettingsViewController

@synthesize settingsTable;
@synthesize autoLetIn;
@synthesize scanByButton;
@synthesize additionalInfo;
@synthesize saveEventData;

#pragma mark - TableViewDelegate Methods
/// Стандартный метод задания заголовков секций таблицы, секции начинаются с 0.
- (NSString *)tableView:(UITableView *)tv titleForHeaderInSection:(NSInteger)section {
    return @"";
}

/// Нестандартный метод задания нижних подписей секций таблицы, секции начинаются с 0.
- (UIView *)tableView:(UITableView *)tableView viewForFooterInSection:(NSInteger)section {
    UIView *customFooterView = [[UIView alloc] init];
    customFooterView.frame = CGRectMake(40.0, 20.0, 300.0, 45.0);
    UILabel *footerText = [[UILabel alloc] initWithFrame:CGRectMake(10.0, 0.0, 300, 32)];
    footerText.textAlignment = UITextAlignmentCenter;
    footerText.font = [UIFont systemFontOfSize:FONT_SIZE_FOOTER];
    footerText.numberOfLines = 0;
    footerText.backgroundColor = [UIColor clearColor];
    if (section == 0) {
        footerText.text = SETTINGS_TEXT_PART0;
    }
    if (section == 1) {
        footerText.text = SETTINGS_TEXT_PART1;
    }
    if (section == 2) {
        footerText.text = SETTINGS_TEXT_PART2;
    }
    if (section == 3) {
        footerText.text = SETTINGS_TEXT_PART3;
    }
    [customFooterView addSubview:footerText];
    return customFooterView;
}

/// Стандартный метод задания высоты строки таблицы, необходим для верного отображения подписей
- (CGFloat)tableView:(UITableView *)tableView heightForFooterInSection:(NSInteger)section {
    return 32;
}

/// Стандартный метод задания количества секций таблицы
- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView { 
    return 4;
}

/// Стандартный метод задания количества ячеек в секции таблицы, определяемой переменной @a section
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section { 
    return 1;
}

/// Стандартный метод задания высоты строки таблицы, заданной в переменной @a indexPath
- (CGFloat) tableView: (UITableView *) tableView heightForRowAtIndexPath: (NSIndexPath *) indexPath {
    return 44;
}

/// Стандартный метод задания содержимого строки таблицы, заданной в переменной @a indexPath
/** Для каждой секции мы выделяем отдельную переменную @a SettingsCell, т.к. качественно все секции
 *  у нас различаются по виду ячеек.
 */
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    if ([indexPath section] == 0) 
    {
        static NSString *SettingsCell0 = @"SettingsCell0";
        UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:SettingsCell0];
        if (cell == nil) {
            cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:SettingsCell0] autorelease];
        }
        UILabel *label0 = [[UILabel alloc] initWithFrame:CGRectMake(20.0, 0.0, 220, 44)];
        label0.text = SETTINGS_LABEL0;
        label0.font = [UIFont boldSystemFontOfSize:FONT_SIZE];
        label0.backgroundColor = [UIColor clearColor];
        [cell addSubview:label0];
        
        [cell addSubview:self.autoLetIn];
        
        cell.selectionStyle = UITableViewCellSelectionStyleNone;
        return cell;
    } 
    if ([indexPath section] == 1) 
    {
        static NSString *SettingsCell1 = @"SettingsCell1";
        UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:SettingsCell1];
        if (cell == nil) {
            cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:SettingsCell1] autorelease];
        }
        UILabel *label1 = [[UILabel alloc] initWithFrame:CGRectMake(20.0, 0.0, 220, 44)];
        label1.text = SETTINGS_LABEL1;
        label1.font = [UIFont boldSystemFontOfSize:FONT_SIZE];
        label1.backgroundColor = [UIColor clearColor];
        [cell addSubview:label1];
        
        [cell addSubview:self.scanByButton];
        
        cell.selectionStyle = UITableViewCellSelectionStyleNone;
        return cell;
    }
    if ([indexPath section] == 2) 
    {
        static NSString *SettingsCell2 = @"SettingsCell2";
        UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:SettingsCell2];
        if (cell == nil) {
            cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:SettingsCell2] autorelease];
        }
        UILabel *label2 = [[UILabel alloc] initWithFrame:CGRectMake(20.0, 0.0, 220, 44)];
        label2.text = SETTINGS_LABEL2;
        label2.font = [UIFont boldSystemFontOfSize:FONT_SIZE];
        label2.backgroundColor = [UIColor clearColor];
        [cell addSubview:label2];
        
        [cell addSubview:self.additionalInfo];
        
        cell.selectionStyle = UITableViewCellSelectionStyleNone;
        return cell;
    }
    if ([indexPath section] == 3) 
    {
        static NSString *SettingsCell3 = @"SettingsCell3";
        UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:SettingsCell3];
        if (cell == nil) {
            cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:SettingsCell3] autorelease];
        }
        UILabel *label3 = [[UILabel alloc] initWithFrame:CGRectMake(20.0, 0.0, 220, 44)];
        label3.text = SETTINGS_LABEL3;
        label3.font = [UIFont boldSystemFontOfSize:FONT_SIZE];
        label3.backgroundColor = [UIColor clearColor];
        [cell addSubview:label3];
        
        [cell addSubview:saveEventData];
        
        cell.selectionStyle = UITableViewCellSelectionStyleNone;
        return cell;
    }
    
    return nil;
}

/// Стандартный метод обработки нажатия на строку таблицы, заданную в переменной @a indexPath
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
    // Убираем выделение строки после нажатия на строку для красоты
    [tableView deselectRowAtIndexPath:indexPath animated:YES];
}

- (void)refreshSettings {
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    [self.autoLetIn setOn:[defaults boolForKey:@"autoLetIn"]];
    [self.scanByButton setOn:[defaults boolForKey:@"scanByButton"]];
    [self.additionalInfo setOn:[defaults boolForKey:@"additionalInfo"]];
    [self.saveEventData setOn:[defaults boolForKey:@"saveEventData"]];
    [defaults synchronize];
    [settingsTable reloadData];
    [[NSNotificationCenter defaultCenter] postNotificationName:@"settingsChanged" object:self];
}

- (void)autoLetInLogic {
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    [defaults setBool:self.autoLetIn.on forKey:@"autoLetIn"];
    [defaults synchronize];
    [settingsTable reloadData];
    [[NSNotificationCenter defaultCenter] postNotificationName:@"settingsChanged" object:self];
}

- (void)scanByButtonLogic {
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    [defaults setBool:self.scanByButton.on forKey:@"scanByButton"];
    [defaults synchronize];
    [settingsTable reloadData];
    [[NSNotificationCenter defaultCenter] postNotificationName:@"settingsChanged" object:self];
}

- (void)additionalInfoLogic {
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    [defaults setBool:self.additionalInfo.on forKey:@"additionalInfo"];
    [defaults synchronize];
    [settingsTable reloadData];
    [[NSNotificationCenter defaultCenter] postNotificationName:@"settingsChanged" object:self];
}

- (void)saveEventDataLogic {
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    [defaults setBool:self.saveEventData.on forKey:@"saveEventData"];
    [defaults synchronize];
    [settingsTable reloadData];
    [[NSNotificationCenter defaultCenter] postNotificationName:@"settingsChanged" object:self];
}

- (void)applicationWillEnterForeground:(NSNotification *)notification {
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    [defaults synchronize];
    [self refreshSettings];
}
#pragma mark - View lifecycle

/*
// Implement loadView to create a view hierarchy programmatically, without using a nib.
- (void)loadView
{
}
*/


// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad
{
    [super viewDidLoad];
    self.autoLetIn = [[UISwitch alloc] initWithFrame:CGRectMake(220.0, 10.0, 50, 44)];
    self.scanByButton = [[UISwitch alloc] initWithFrame:CGRectMake(220.0, 10.0, 50, 44)];
    self.additionalInfo = [[UISwitch alloc] initWithFrame:CGRectMake(220.0, 10.0, 50, 44)];
    self.saveEventData = [[UISwitch alloc] initWithFrame:CGRectMake(220.0, 10.0, 50, 44)];
    
    [self refreshSettings];
        
    [self.autoLetIn addTarget:self action:@selector(autoLetInLogic) forControlEvents:(UIControlEvents)UIControlEventValueChanged];
    [self.scanByButton addTarget:self action:@selector(scanByButtonLogic) forControlEvents:(UIControlEvents)UIControlEventValueChanged];
    [self.additionalInfo addTarget:self action:@selector(additionalInfoLogic) forControlEvents:(UIControlEvents)UIControlEventValueChanged];
    [self.saveEventData addTarget:self action:@selector(saveEventDataLogic) forControlEvents:(UIControlEvents)UIControlEventValueChanged];
    
    UIApplication *app = [UIApplication sharedApplication];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(applicationWillEnterForeground:) name:UIApplicationWillEnterForegroundNotification object:app];
   
    // При первом запуске приложения задаем начальные параметры вручную, так как приложение Settings
    // еще не загрузило настройки из списка.
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    NSLog(@"self.autoLetIn.isOn = %d",self.autoLetIn.isOn);
    NSLog(@"self.scanByButton.isOn = %d",self.scanByButton.isOn);
    NSLog(@"self.additionalInfo.isOn = %d",self.additionalInfo.isOn);
    NSLog(@"self.saveEventData.isOn = %d",self.saveEventData.isOn);
    NSLog(@"[defaults boolForKey:autoLetIn] = %d",[defaults boolForKey:@"autoLetIn"]);
    NSLog(@"[defaults boolForKey:scanByButton] = %d",[defaults boolForKey:@"scanByButton"]);
    NSLog(@"[defaults boolForKey:additionalInfo] = %d",[defaults boolForKey:@"additionalInfo"]);
    NSLog(@"[defaults boolForKey:saveEventData] = %d",[defaults boolForKey:@"saveEventData"]);
}

- (void)viewDidUnload
{
    [super viewDidUnload];
    // Release any retained subviews of the main view.
    // e.g. self.myOutlet = nil;
    
    [[NSNotificationCenter defaultCenter] removeObserver:self];
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

- (void)dealloc 
{
    [self.autoLetIn release];
    [self.scanByButton release];
    [self.additionalInfo release];
    [self.saveEventData release];
    [super dealloc];
}

@end
