//
//  TicketDetailsViewController.m
//  QR Scanner
//
//  Created by Apple Developer on 22.12.11.
//  Copyright (c) 2011 __MyCompanyName__. All rights reserved.
//

#import "TicketDetailsViewController.h"

@implementation TicketDetailsViewController

@synthesize ticketDetailsURL;
@synthesize ticketDetails;

- (void)didReceiveMemoryWarning
{
    // Releases the view if it doesn't have a superview.
    [super didReceiveMemoryWarning];
    
    // Release any cached data, images, etc that aren't in use.
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
    
    //ticketDetailsURL = @"http://auratek.ru";
    NSLog(@"URL = %@",ticketDetailsURL);
    
    // Открываем страничку данных по билету. Правильность формирования странички обеспечивается
    // в TicketListViewController.
    [ticketDetails loadRequest:[NSURLRequest requestWithURL:[NSURL URLWithString:ticketDetailsURL]]];
}


- (void)viewDidUnload
{
    [super viewDidUnload];
    // Release any retained subviews of the main view.
    // e.g. self.myOutlet = nil;
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
    // Return YES for supported orientations
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}

@end
