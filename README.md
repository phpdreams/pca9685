# PCA9685 PWM Controller
**PHP Class to enable control of the Adafruit PCA9685 16-channel PWM/Servo I2C Interface**

I wrote this to allow me to control the Adafruit PCA9685 PWM board to do some custom LED lighting in my living room.  The only code I could find for this was in Python.  I've been a PHP developer for nearly 20 years, so I naturally wanted to code in something I was more comfortable with.

It took a lot of reading and piecing together a way to get this working including reviewing quite a bit of the 52-page datasheet for the PCA9685 chip (linked below). In the end, it was worth it as I can now control all the channels of this great little board from a web page on any device in the house.

As much trouble as I had, I figured others might have the same issues.  I decided I'd put the code together as a **Composer** package and make it available on **Packagist**.  I hope it helps somebody else.

### Requirements

_I'm not going to show how you can get PHP7 & Nginx running on your Raspberry Pi, there are lots of other resources for that kind of thing._

This code does require that you have already enabled **I2C** support and installed the `i2c-tools`.

For my new (purchased Jan 2017) Raspberry Pi 3, this was easy as I only had to edit the `/boot/config.txt` file and uncomment the `dtparam=i2c_arm=on` line and reboot.

Then I installed the I2C tools:
```
sudo apt-get install -y python-smbus
sudo apt-get install -y i2c-tools
```
You can read up a bit more here:
https://learn.adafruit.com/adafruits-raspberry-pi-lesson-4-gpio-setup/configuring-i2c

### Resources

Interface board can be purchased directly from Adafruit here:

https://www.adafruit.com/product/815

Datasheet for the PCA9685: 

https://cdn-shop.adafruit.com/datasheets/PCA9685.pdf

## Installation

Installation is best accomplished with Composer (https://getcomposer.org/)
```
composer require "phpdreams/pca9685":"dev-master"
```

## Usage

```php
require_once(__DIR__ . '/vendor/autoload.php');

use PHPDreams\PCA9685\PCA9685;

$pwm = new PCA9685();
```

I've setup two ways to set the PWM level of any given channel.  You can do it by percent or by setting
the on and off points.
  
#### Set Channel Output by Percent:
```php
$pwm->setPWMpercent($channel, $percent);
```
Channel is a number from 0 to 15. 

#### Manually setting the on and off points:
```php
$pwm->setPWM($channel, $countOff, $countOn = 0);
```
You can set the on and off points in the 4096-count cycle.  So setting a channel to turn off at at 2048 would be a 50% duty cycle.

_If you don't set an 'On' point, it will default to 0._

#### Set ALL channels to a value:
```php
$pwm->setAll($channel, $countOff, $countOn = 0);
```

#### Set refresh frequency in Hz:
```php
$pwm->setFrequency($frequency);
```
_Defaults to 200Hz refresh._