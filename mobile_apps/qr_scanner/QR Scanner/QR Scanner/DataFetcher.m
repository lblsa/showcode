//
//  DataFetcher.m
//  QR Scanner
//
//  Created by Apple Developer on 20.12.11.
//  Copyright (c) 2011 __MyCompanyName__. All rights reserved.
//

#import "DataFetcher.h"
#import "ASIHTTPRequest.h"

@implementation DataFetcher

- (NSString *)grabURL:(NSString *)URLString
{
    NSURL *url = [NSURL URLWithString:URLString];
    NSString *response = [[NSString alloc] initWithString:@""];
    
    ASIHTTPRequest *request = [ASIHTTPRequest requestWithURL:url];
    //[request setDelegate:self];
    [request startSynchronous];
    NSError *error = [request error];
    if (!error) {
        response = [request responseString];
        //NSString *contentType = [[request responseHeaders] objectForKey:@"Content-Type"];
        //NSLog(@"Content-Type = %@", contentType);
        NSLog(@"Server response = %@",response);
    } else {
        NSLog(@"Error occured, error code = %d, domain = %@",error.code,error.domain);
        return nil;
    }
    return response;
}

- (void)requestFinished:(ASIHTTPRequest *)request
{
    // Use when fetching text data
    NSString *responseString = [request responseString];
    NSLog(@"Server response = %@",responseString);
}

- (void)requestFailed:(ASIHTTPRequest *)request
{
    NSError *error = [request error];
    NSLog(@"Error occured, error code = %d, domain = %@, userInfo",error.code,error.domain);
    UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Внимание!" message:@"При попытке соединиться с сервером произошла непредвиденная ошибка. Код ошибки:" delegate:self cancelButtonTitle:@"ОК" otherButtonTitles: nil];
    [alert show];
    [alert release];
}

@end
