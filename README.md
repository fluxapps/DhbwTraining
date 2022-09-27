ILIAS-Plugin DhbwTraining
============
![GitHub release (latest SemVer)](https://img.shields.io/github/v/release/fluxapps/DhbwTraining?style=flat-square)
![GitHub closed issues](https://img.shields.io/github/issues-closed/fluxapps/DhbwTraining?style=flat-square&color=success)
[![GitHub issues](https://img.shields.io/github/issues/fluxapps/DhbwTraining?style=flat-square&color=yellow)](https://github.com/fluxapps/DhbwTraining/issues)
![GitHub closed pull requests](https://img.shields.io/github/issues-pr-closed/fluxapps/DhbwTraining?style=flat-square&color=success)
![GitHub pull requests](https://img.shields.io/github/issues-pr/fluxapps/DhbwTraining?style=flat-square&color=yellow)
[![GitHub forks](https://img.shields.io/github/forks/fluxapps/DhbwTraining?style=flat-square&color=blueviolet)](https://github.com/fluxapps/DhbwTraining/network)
[![GitHub stars](https://img.shields.io/github/stars/fluxapps/DhbwTraining?style=flat-square&color=blueviolet)](https://github.com/fluxapps/DhbwTraining/stargazers)
[![GitHub license](https://img.shields.io/github/license/fluxapps/DhbwTraining?style=flat-square)](https://github.com/fluxapps/DhbwTraining/blob/main/LICENSE.md)

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

## Maintenance
fluxlabs ag, support@fluxlabs.ch

This project is maintained by fluxlabs.
