# Refactoring `ScheduleManager.php`

- Added return type as `string` for `stringFromURL`
- Condition to check the current time can be a function
```php
gettimeofday( true ) > self::startHour() && gettimeofday( true ) < self::endHour()

// to
private static function isCurrTimeInBetween(): boolean {
    return gettimeofday( true ) > self::startHour() && gettimeofday( true ) < self::endHour();
}
```
- The two if conditions are never executed always. We can make it a if else statement.
```php
if ( self::isCurrTimeInBetween() ) {
    $hM->manageHeating( $t, $threshold, true );
} else  {
    $hM->manageHeating( $t, $threshold, false );
}
```
- Return value from `endHour()` and `startHour()`.
- Converted type `boolean` to `bool`.
- Remove the `if else` condition and replace the 3 parameter of `manageHeating()`
```php
if ( self::isCurrTimeInBetween() ) {
    $hM->manageHeating( $t, $threshold, true );
} else  {
    $hM->manageHeating( $t, $threshold, false );
}

// to

$hM->manageHeating( $t, $threshold, self::isCurrTimeInBetween() );
```


# Refactoring `HeatingManagerImpl.php`
- Converted type `boolean` to `bool`.
- Try catch can be enclosed once instead of each if block, since we are not doing things differently in either case.
- Since both the if conditions are have `&& $active` we can add a negate and do an early return.
```php
if (!$active) {
    return;
}
```
- Common code to create socket and connect can be moved before the if condition

```php

if ( !( $s = socket_create( AF_INET, SOCK_STREAM, 0 ) ) ) {
    die( 'could not create socket' );
}
if ( !socket_connect( $s, 'heater.home', 9999 ) ) {
    die( 'could not connect!' );
}
```
- Common code to send and close the socket can be moved after the if condition
```php
socket_send( $s, $m, strlen( $m ), 0 );
socket_close( $s );
```
