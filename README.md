# vCard Platform Web Application - Unidade Curricular DAD

This repository contains the source code for the vCard Platform Web Application, which is part of the Distributed Application Development (DAD) project. The web application is implemented as a Single Page Application (SPA) using the Vue.js framework for the frontend, and it communicates with a backend implemented with Laravel for the Restful API and Node.js for the WebSocket server.

## Objective
The objective of this project is to develop a web application for managing virtual debit cards (vCards) associated with mobile phone numbers. Users can perform debit transactions, access transaction history, manage profile and settings, and more.

## Features
vCard Creation and Dismissal, Authentication, Profile, Settings
Users can create a new vCard by providing a phone number, password, name, email, and optional photo.
Authentication is done using phone number and password.
Users can manage their profile information, confirmation code, and password.
Configuration of credit and debit transaction categories is available.
Users can dismiss (remove) their vCard if the balance is zero.

## Transactions
Users can conduct debit transactions, specifying transaction value, payment type, and payment reference.
Transaction history is available, sorted by datetime, with pagination and filters.
Transactions can have descriptions and categories.
Credit transactions can be manually added by administrators.

## Administration
Administrators can manage vCards, including viewing, filtering, blocking/unblocking, and changing debit limits.
Default debit and credit categories can be configured by administrators.

## Statistics
Statistical information is provided visually (graphs) and/or textually (tables) for vCard owners and administrators.
Automatic Refresh and Notifications
The application supports automatic refresh to update content based on external events.
Notifications are provided for relevant events such as receiving credit or vCard management actions.

## Architecture
The web application architecture consists of a Vue.js frontend communicating with a Laravel backend for the Restful API and a Node.js WebSocket server for real-time communication.
