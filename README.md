# PCA9685 PWM Controller
PHP Class to enable control of the Adafruit PCA9685 16-channel PWM/Servo I2C Interface

Interface board can be purchased directly from Adafruit here:

https://www.adafruit.com/product/815

Datasheet for the PCA9685: 

https://cdn-shop.adafruit.com/datasheets/PCA9685.pdf

# Installation

Installation is best accomplished with Composer (https://getcomposer.org/)
```
composer require "phpdreams/pca9685"
```

# Usage

```
use PHPDreams\PCA9685\PCA9685;
$pwm = new PCA9685();
```

I've setup two ways to set the PWM level of any given channel.  You can do it by percent or by setting
the on and off points.
  
By Percent:
```
$pwm->setPWMpercent($channel, $percent);
```

Manually setting the on and off points:
```
$pwm->setPWM($channel, $countOff, $countOn = 0);
```
You can set the on and off points in the 4096-count cycle.  So setting a channel to turn off at at 2048 would be a 50% duty cycle.

_If you don't set an 'On' point, it will default to 0._