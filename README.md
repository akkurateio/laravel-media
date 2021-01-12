# Laravel Media

Module de gestion de media pour l’écosystème akkurate.

L’objet de ce package est d’étendre le package spatie/laravel-medialibrary, en permettant notamment d’importer des media sans forcément les rattacher immédiatement à un model, ou à l’inverse d’attacher le même media à plusieurs models (une image pour illustrer un article de blog qui pourrait servir dans un carousel ou être utlisée comme avatar pour un compte ou un utlisateur).

## Installation (le package est installé de base avec subvitamine/akkurate-laravel-boilerplate)

``` bash
composer require akkurate/laravel-media
```

Publier le fichier de configuration:
```bash
php artisan vendor:publish --provider="Akkurate\LaravelMedia\LaravelMediaServiceProvider" --tag="config"
```

Pour la gestion des tags, vérifier que la locale est bien définie dans le .env

```
APP_LOCALE=fr
```

## Concepts

La table et le model Media sont inchangés, mais un Media n’est plus rattaché directement à un model de l’application mais à un model générique Resource.

Chaque model à qui doit pouvoir être associé à un media doit utliser le trait HasResources.

```
use Akkurate\LaravelMedia\Traits\HasResources;

class Model
{
    use HasResources;
}
```

Une resource étend le media en ajout un compte de rattachement, un utilisateur, un texte alternatif, une légende.

Le model Media utilisé est le model media custom Akkurate\LaravelMedia\Models\Media, qui étend le model Media de Spatie. Ce model custom n’ajoute aucune méthode ni aucune relation en v1, mais, dans une perspective d’évolution, le permettra sans modifier la structure ni la config.

Une table pivot model_resource permet d’attribuer la même resource à plusieurs models, quels qu’ils soient.

## Config

Chaque resource est enregistrée avec un certain nombre de conversions standardisées, activables dans un fichier de configuration pour l’ensemble du projet. (Et non plus pour chaque model individuellement.)

```
'conversions' => [
    'thumb' => true,
    'preview' => true,
    'square' => true,
    '4:3' => true,
    '16:9' => true,
],
```

## Enregistrement d’un media

Avant de créer un media, créer au préalable une resource

```
$resource = Resource::create([
    'name' => $request->alt,
    'user_id' => auth()->user()->id,
    'account_id' => auth()->user()->account_id,
]);
```

Une fois la resource créée, lui attacher le media
```
$resource->addMedia($file)
```

La resource peut ensuite être attachée à n’importe quel model
```
$user = User::find(1);
$user->resources()->attach($resource, ['legend' => 'Image pour l’utilisateur 1']);

$account = Account::find(1);
$account->resources()->attach($resource, ['legend' => 'Image pour le compte 2']);
```

Le même media pouvant avoir diverses utilisations, il est possible de définir un champ « legend » au niveau de la relation.

## Utilisation d’un media

La récupération du ou des media attachés à un model nécessite une étape supplémentaire (le passage par la table pivot media_model_resource).

```
$model->resources->first()->getFirstMedia()
```

Des méthodes permettent de faciliter les appels courants
```
$model->getLastResource() // =  $model->resources->last();
$model->getLastResourceMedia() // =  $model->resources->last()->getFirstMedia();
$model->getLastResourceMediaUrl() // =  $model->resources->last()->getFirstMediaUrl();
$model->getPreview() // =  $model->resources->last()->getFirstMediaUrl('default', 'preview');
$model->getSquare() // =  $model->resources->last()->getFirstMediaUrl('default', 'square');
$model->get4_3() // =  $model->resources->last()->getFirstMediaUrl('default', '4:3');
```

### Gestion d’un model une seule resource

Dans la vue edit
```
@component('back::atomicdesign.atoms.has-media', ['object' => $model, 'form' => $form, 'resource' => $model->getLastResource()])@endcomponent
```

### Gestion d’un model avec plusieurs resources

Dans la vue edit
```
@component('back::atomicdesign.atoms.has-media', ['multiple' => true, 'object' => $model, 'form' => $form, 'resource' => $model->resources])@endcomponent
```

Dans la method edit du controller, après l’update
```
$model->syncResources($request);
```

## API

### Medias
```bash
GET /media/medias?include=resource
POST /media/medias // params: *image, *name, alt, legend
GET /media/medias/{id}
PUT,PATCH /media/medias/{id} // params: *name, alt, legend
DELETE /media/medias/{id}
```

### Resources
```bash
GET /media/resources?include=media,tags&filter[tags]=tag1,tag3 // la resource ne remonte que si les deux tags sont attachés
GET /media/resources/{id}

// Attacher une resource à n’importe quel model
POST /media/resources/{id}/attach // params: *model_type, *model_id
// Supprimer une association resource/model
POST /media/resources/{id}/detach // params: *model_type, *model_id

// Gestion des tags sur les resources
POST /media/resources/{id}/tags/attach // params: *tag
POST /media/resources/{id}/tags/detach // params: *tag
```
