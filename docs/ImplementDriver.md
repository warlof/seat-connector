# Structure

Your driver implementation must meat the minimal structure bellow :

```
/
/Config
/Config/{driver}-connector.config.php
/Config/seat-connector.config.php
/Drivers
/Drivers/{Driver}Client.php
/Drivers/{Driver}User.php
/Drivers/{Driver}Set.php
/{Driver}ConnectorServiceProvider.php
```

# Configuration

The file called `seat-connector.config.php` must contain a structure like this :

```
[
    'label' => {driver label},
    'icon'  => {driver icon},
    'client' => {driver client},
];
```

Upper attributes functions are described in the table bellow :

| Attribute | Function                                                                                 |
| --------- | ---------------------------------------------------------------------------------------- |
| `label`   | It is the value which will be used as display in SeAT. You can use a translation string  |
| `icon`    | Use a Font-Awesome class - it will be used on `identities` page for display purpose only |
| `client`  | Provide the **FQDN** to your `IClient` implementation                                    |

# Classes

## IClient

IClient is the entry point of your driver. It will implement methods which will allow the system to communicate with your platform.

It must contain the following methods :
 - getInstance(): IClient
 - getUsers(): array
 - getSets(): array
 - getUser(string id): ?IUser
 - getSet(string id): ?ISet

### getInstance()

This method is used by the connector to access your driver implementation and the platform to which it's connected.
It must return an instance of IClient.

At pattern point of view, this class is a singleton and constructor will never be directly used by the connector itself.

### GetUsers()

This method must return a list of `IUser` which are user registered on the platform.
Returned users can be active or not.

### getUser()

This method can return an `IUser` related to a registered user on the platform.
It may return `null` which mean no user related to the `ID` sent in parameter has been found.

### GetSets()

This method must return a list of `ISet` which are the "thing" you want limit access using connector rules.
It can be either channels, server groups, or anything else.

### GetSet()

This method can return an `ISet` related to a "thing" you want limit access using connector rules.
It may return `null` which mean no "thing" related to the `ID` sent in parameter has been found.