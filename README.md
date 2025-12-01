# Hoerbiger Neos Distribution

## What's different

### Promoting users
To promote users from standard editor role to ChiefEditor (Hoerbiger.Shared:ChiefEditor) run the following command (adjust username to your needs):

```sh
./flow editor:promote 'HOERBIGER-COM\uext_abc'     
```

## Installation

Install dependencies via:
```sh
make install
```

Build frontend via 
```sh
make build
```

## Running the site locally

You can start a development server via:

```sh
make up
```

## Clone project data

You can clone the project data directly with make. This command shows up the list and ask for the preset:

```
make clone
```

or type the preset directly:

```sh
make clone-develop

make clone-staging

make clone-main
```

## Versioning

This project follows the trunk-based approach for continuous integration.

All branches are directly derived from main and named after the corresponding ticket's number, regardless of whether they're features, bugfixes or anything else.

Ticket branches are independently merged to develop, then staging, then main. All branches except master can at all times be deleted and recreated from master.

### Branching Model

    12345                        +- Commit -+----+----+
                                /            \    \    \
    develop              +------------- Merge -+------------------------> develop
                        /      /                     \    \
    staging      +------------------------- Merge -+-----------------> staging
                /     /      /                              \
    main   ----+-----+------+------------------------ Merge -+----------> master

## Translation

### Translate all pages from English to a new language
```sh
./flow translation:refresh --source-language en --target-language <target language>
```

### Translate a single page from English to another language
```sh
./flow translation:refresh --source-language en --target-language <target language>
```

### Bypass deepl translation on a test server
By enabling translation bypass, sending translation requests to DeepL is avoided and a suffix (-translated) is added to the original text.
```sh
Hoerbiger:
    HoerbigerCom:
        translation: 
            bypassEnabled: false
```

### Non-translatable properties synchronization
Synchronization strategy is controlled using an option in property definition in node type configuration
```sh
properties:
    foo:    
        ui:        
            options:
                synchronizationStrategy: <once|sync>
```
Default strategy is sync. If a property is to be translated only once when adopted and get separated from the original property the once strategy can be set.

### Adding a new language

New languages have to be registered in several places:

1. Content dimension configuration, see `Hoerbiger.Shared/Configuration/Settings.ContentDimensions.yaml`
2. Content lifecycle configuration, see `Hoerbiger.HoerbigerCom/Configuration/Settings.Lifecycle.yaml`
3. Translation configuration, see `Hoerbiger.HoerbigerCom/Configuration/Settings.Translations.yaml`
4. Policy adjustments; there is probably a new editor role to be configured, see `Hoerbiger.Shared/Configuration/Policy.yaml`
