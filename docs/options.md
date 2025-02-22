# Options

Le fichier de configuration est localisé à `config/packages/olix_bo.yaml`

### Default configuration

~~~ yaml
olix_back_office:
    # Options du thème
    options:
        # Mode sombre activé ou pas
        dark_mode: false
        # Mise en page mode boite 1250 px
        boxed: false
        navbar:
            # Barre de navigation fixée ou pas
            fixed: false
            # Thème de la barre de navigation (dark|light)
            theme: light
            # Couleur de fond de la barre de navigation
            color: 
        sidebar:
            # Barre latérale fixée ou pas
            fixed: true
            # Réduction de la barre latérale ou pas
            collapse: false
            # Thème de la barre (dark|light)
            theme: dark
            # Couleur du menu activé
            color: primary
            # Menu en mode flat
            flat: false
            # Menu en mode legacy
            legacy: false
            # Menu compact
            compact: false
            # Menu avec indentation des sous menus
            child_indent: false
        footer:
            # Pied de page fixé ou pas
            fixed: false

    security:
        # Si on active le menu de gestion des utilisateurs
        menu_activ: false
        class:
            # Class de l'entité utilisateur
            user: App\Entity\User
            # Class de formulaire de modification des données de l'utilisateur
            form_user: Olix\BackOffice\Form\UserEditType
            # Class de formulaire du profile des données de l'utilisateur
            form_profile: Olix\BackOffice\Form\UserProfileType
~~~