# Tests

## Introduction

This document concerns everything that in the `tests` directory.

## Laravel tests

The top-level directory structure has 3 elements:
* Cases - This is where all test cases go
* Setup - This is where the setup of the test environment happens
* Shared - This is where all shared things go

### Cases

The Cases directory consists of a further three elements:
* Feature - These are feature tests (a feature test covers one Use Case)
* Issue - These are tests against reported and fixed issues (so that they do not
happen again)
* Unit - These are unit tests (a unit test covers one particular class in the
system)

#### Unit Tests

##### UT0001_UserRepositoryTests
