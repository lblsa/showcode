//
//  Ticket.h
//  QR Scanner
//
//  Created by Apple Developer on 22.12.11.
//  Copyright (c) 2011 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface Ticket : NSObject {
    NSString *ticketID;
    NSString *eventID;
    NSString *buyerPhone;
    NSString *purchaseDate;
    NSString *ticketURL;
    /** Переменная статуса билета. Принимает следующие значения:
     *  -100 - значение при создании объекта билета
     *  -1 - указанный билет на сервере не найден
     *   0 - билет забронирован, но не оплачен
     *   1 - билет оплачен и действителен, владельца билета можно пропускать
     *   2 - бронь просрочена, билет не оплачен
     *   3 - билет уже использован
     *   4 - билет сегодня не действует (для билетов с датами прохода)
     */
    NSInteger ticketStatus;
    NSString *placeRow;
    NSString *placeColumn;
}

@property (nonatomic, copy) NSString *ticketID;
@property (nonatomic, copy) NSString *eventID;
@property (nonatomic, copy) NSString *buyerPhone;
@property (nonatomic, copy) NSString *purchaseDate;
@property (nonatomic, copy) NSString *ticketURL;
@property (nonatomic) NSInteger ticketStatus;
@property (nonatomic, copy) NSString *placeRow;
@property (nonatomic, copy) NSString *placeColumn;
@end
