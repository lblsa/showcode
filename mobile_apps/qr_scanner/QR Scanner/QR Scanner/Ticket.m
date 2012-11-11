//
//  Ticket.m
//  QR Scanner
//
//  Created by Apple Developer on 22.12.11.
//  Copyright (c) 2011 __MyCompanyName__. All rights reserved.
//

#import "Ticket.h"

@implementation Ticket

@synthesize ticketID;
@synthesize eventID;
@synthesize buyerPhone;
@synthesize purchaseDate;
@synthesize ticketURL;
@synthesize ticketStatus;
@synthesize placeRow;
@synthesize placeColumn;

#pragma mark - Memory Management

- (id)init
{
    if ( self = [super init] )
    {
        ticketID = [[NSString alloc] init];
        eventID = [[NSString alloc] init];
        buyerPhone = [[NSString alloc] init];
        purchaseDate = [[NSString alloc] init];
        ticketURL = [[NSString alloc] init];
        ticketStatus = -100;
        placeRow = [[NSString alloc] init];
        placeColumn = [[NSString alloc] init];
    }
    return self;
}

- (void)dealloc {
    [ticketID dealloc];
    [eventID dealloc];
    [buyerPhone dealloc];
    [purchaseDate dealloc];
    [ticketURL dealloc];
    [placeRow dealloc];
    [placeColumn dealloc];
    [super dealloc];
}


@end
