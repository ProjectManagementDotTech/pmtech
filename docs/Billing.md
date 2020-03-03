# Billing

Date: 01 Mar 2020

## Introduction

This document describes the process flows around billing. It serves as a guide
to implement the various routes (both Laravel and VueJS), and as a means to test
whether or not these make actual sense from a user experience point of view.

## Use Cases

### UC0001 - Workspace requires subscription set up

The system determines that the configuration indicates that a subscription
charge needs to be paid __and__ that the charge is more than 0.

#### Flow

* System sends email to workspace owner with link to pay and setup subscription;
* Owner clicks on link and (_after authenticating_) is presented with either
  * A list of already known payment methods *and* a button to setup a new
  payment method __or__
  * An input screen to setup a new payment method outright
* When submitting the payment method to use, the screen is cloaked while the
system is processing the payment
* When the payment is successful, a "Thank you" note is displayed, and the user
can navigate to any part of the system.
* END

#### Details

The distinction between showing a list of known payment methods or the input
screen, is based solely on the fact whether the user has already logged payment
methods in Stripe. If so, the list + button is shown. If not, the input screen
is shown.

### UC0002 - User reviews payments methods

### UC0003 - User reviews bills

### UC0004 - User needs to settle outstanding charge

### UC0005 - Workspace subscription changes plan

### UC0006 - User cancels workspace subscription

### UC0007 - Workspace with subscription is transferred to new owner

### UC0008 - User deletes payment method

In the list of payment methods, a button is available with which a user can
delete a payment method. This button is only available for non-default payment
methods.

### UC0009 - User updates payment method

### UC0010 - User adds new payment method

A dailog is shown in which the user can add the payment method details. Stripe
will validate the payment method, and the payment is then stored with the user.
 
## Implementation

### VueJS Components

#### AddPaymentMethod

This component dynamically creates a setupIntent with Stripe, loads the Stripe
JavaScript and sets up the payment input UI components.

Upon clicking the add button, the AddPaymentMethod component validates the
inputted payment method details with Stripe. If there is an error at this stage,
that error is displayed.

If there were no errors, Stripe will have responded with a payment method
identifier. This identifier is then submitted to our API to be stored alongside
the user record.

The component then emits the "added" signal.

A "Cancel" is also displayed. This button emits the "cancel" signal.
 
#### PaymentMethodsTable

This component shows all payment methods and allows each to be deleted or
selected for payment purposes.

It also shows a button under the list to allow the user to add a new payment
method.

The component receives all payment methods via a prop.

The component emits the following signals:
* deleted - A payment method was deleted; the payment method ID is sent along
* selected - A payment method was selected; the payment methods ID is sent along
* add - Show the input screen to add a new payment method
