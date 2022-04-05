ILIAS-Plugin DhbwTraining
============


## Installation
Start at your ILIAS root directory
```bash
mkdir -p Customizing/global/plugins/Services/Repository/RepositoryObject
cd Customizing/global/plugins/Services/Repository/RepositoryObject
git clone https://github.com/fluxapps/DhbwTraining.git
```
As ILIAS administrator go to "Administration->Plugins" and install/activate the plugin.  

## Usage
### Competences

"competences": {
    "4": 6,
    "7": 5
}
![](docs/competence_skill_id.png)

![](docs/Portfolio.png)
    
### Progress Meters

"progress_meters": [
{
  "progressmeter_type": "0",
  "title": "Ihr Fortschritt",
  "max_width_in_pixel": "",
  "max_reachable_score": "100",
  "required_score": "80",
  "primary_reached_score": "40",
  "secondary_reached_score": "60"
}
]

![](docs/Progressmeter.png)

### Requirements
* ILIAS 6.0
* PHP >=7.0
* Recommender Phython Software

## Contributing :purple_heart:
Please ...
1. ... register an account at https://git.fluxlabs.ch
2. ... create pull requests :fire:

## Adjustment suggestions / bug reporting :feet:
Please ...
1. ... register an account at https://git.fluxlabs.ch
2. ... ask us for a Service Level Agreement: support@fluxlabs.ch :kissing_heart:
3. ... Read and create issues
