# Programmation orientée objet

La POO représente un changement de paradigme significatif. Nous allons parler dans cette partie des différentes notions à savoir pour pouvoir concevoir une architecture qui s'articule autour d'objets, capables de représenter des structures plus complexes que des variables simples (int, string, bool, etc...).

## Les classes

Pour représenter ces structures plus complexes, on peut commencer par définir des **classes** dans notre application.

Une classe représente un **nouveau type** utilisable dans notre application. C'est comme un squelette, une structure, ou un template, si vous voulez, qui représente une notion complexe présente dans notre application.

Par exemple, si nous voulons manipuler des produits dans notre application, au lieu de définir un tableau associatif avec clés et valeurs, nous pouvons **structurer** notre application de manière plus rigoureuse en définissant un nouveau type `Produit`.

Par la suite, nous pourrons instancier des objets de type `Produit`. Nous allons donc parler dans un premier temps de la définition d'une classe, puis de l'instanciation d'objets.

### 1. Définition d'une classe

On utilise le mot-clé `class` pour définir un nouveau type :

```php
class Produit
{}
```

#### Attributs & méthodes

Dans la définition d'une classe, on va pouvoir ajouter des **attributs**. Ces attributs appartiennent donc à la classe.

On peut généralement appliquer le verbe **avoir** quand veut déterminer les différents attributs d'une classe. Par exemple : "Un produit a un nom et un prix" nous donne donc :

```php
class Produit
{
  public $nom;
  public $prix;
}
```

> Note : à partir de PHP 7.4, il est possible de typer les attributs d'une classe : `public string $nom` par exemple

L'autre intérêt de créer de nouveaux types structurés dans notre application est de lui donner certaines **capacités**.

Ces capacités se matérialisent sous forme de **méthodes** de classe.

Par exemple, nous pourrions dire que notre classe `Produit` possède la capacité de renvoyer le prix TTC du produit, à partir de son attribut `prix` et d'un taux passé en paramètre :

```php
class Produit
{
  public $nom;
  public $prix;

  public function prixTTC(float $taux): float
  {
    return $this->prix + $this->prix * $taux;
  }
}
```

> Dans une méthode, on peut accéder aux attributs de la même classe en utilisant le mot-clé `$this`

Chaque attribut ou méthode possède une **portée** : `public`, `protected` et `private`.

#### Portées

Les portées sont définies pour indiquer au code qui va instancier un objet d'un certain type ce à quoi il peut accéder ou non.

Dans la classe `Produit` que nous avons définie plus haut, les 2 attributs sont publiques.

Cela signifie qu'on pourra y accéder directement depuis une instance d'objet avec la syntaxe suivante :

```php
$monNomDeProduit = $monInstanceDeProduit->nom;
```

Si on rend un attribut `private` ou privé, alors on ne peut plus accéder à l'attribut directement depuis une instance.

> La portée `protected` sera expliquée plus tard, dans le cadre de l'héritage.

En réalité, nous allons définir ces attributs comme `private` afin de respecter le principe d'**encapsulation**.

#### Encapsulation

L'encapsulation consiste à placer les attributs d'une classe en `private`, puis de définir des méthodes d'**accession** et de **modification** de ces attributs, ou encore des **getters** et des **setters**.

L'intérêt principal de ce principe est de permettre à la classe de garder le contrôle sur ses attributs. On décide de la façon dont on va pouvoir renvoyer un attribut à tout code extérieur manipulant une instance de cette classe.

> Un autre intérêt peut être de passer un attribut en lecture seule par exemple, donc ne pas déclarer de méthode de modification pour cet attribut. Vu que l'attribut est privé, et qu'on ne dispose que d'une méthode publique d'accession à cet attribut, alors on ne peut que le récupérer, pas le modifier

Réécriture de la classe `Produit` pour respecter le principe d'encapsulation :

```php
class Produit
{
  private $nom;
  private $prix;

  // Getter / Accesseur, pour l'encapsulation de notre attribut $nom
  public function getNom(): ?string
  {
    // Ici on décide de renvoyer tout le temps le nom d'un produit en majuscules
    return strtoupper($this->nom);
  }

  // Setter / Modificateur, toujours pour l'encapsulation
  public function setNom(string $nom): void
  {
    $this->nom = $nom;
  }

  public function getPrix(): float
  {
    return $this->prix;
  }

  public function setPrix(float $prix)
  {
    $this->prix = $prix;
  }

  public function getPrixTtc(float $taux): float
  {
    return $this->prix + $this->prix * $taux;
  }
}
```

### 2. Instanciation d'objets de classes

Une fois notre structure définie, à l'extérieur de la classe, nous avons la possibilité d'instancier et manipuler des produits. Pour ça, on peut tout simplement déclarer une variable et utiliser le mot-clé `new` avec le type souhaité :

```php
$produit = new Produit();
```

Une fois qu'on possède une instance de classe, on a accès à ses méthodes **publiques** :

```php
$produit->setNom("Téléviseur");
echo $produit->getNom(); // Affichera "Téléviseur"

$produit->setPrix(800);
echo $produit->getPrixTTC(0.2); // Affichera 960
```

### Constructeur

Lors de l'instanciation d'une classe, on peut vouloir initialiser certaines valeurs par exemple. Pour cela, il est possible de définir un **constructeur** de classe, méthode qui s'exécutera automatiquement lors de l'instanciation de la classe :

```php
class Produit
{
  // ...

  public function __construct(string $nom = "Téléviseur")
  {
    $this->nom = $nom;
  }
}
```

Pour utiliser le constructeur, on peut alors instancier notre objet avec des paramètres, comme si on appelait une fonction :

```php
// Mon produit aura pour nom "Téléphone", mais si j'avais instancié mon produit sans passer d'argument il se serait automatiquement appelé "Téléviseur"
$produit = new Produit("Téléphone");
```

> En PHP, le constructeur d'une classe s'appelle une méthode **magique**, tout simplement car elle est automatiquement appelée dans un certain contexte (ici l'instanciation d'un objet de cette classe). Le nom d'une méthode magique est toujours précédé de 2 "underscores", caractère `_`

### Constantes de classe

Il est possible de définir des constantes dans une classe. Cela peut être utile pour centraliser des données qu'on ne souhaite pas modifier au niveau de la classe elle-même, et ainsi pouvoir travailler avec dans ses différentes méthodes :

```php
class Email
{
  private $value;

  private const BLACKLIST = ["yopmail.com", "mailinator.com", "tempmail.com"];

  public function __construct(string $value)
  {
    $this->value = $value;
  }

  // ...

  public function isSpam(): bool
  {
    if (!$this->isValid()) {
      throw new InvalidArgumentException("Email incorrect");
    }

    $emailParts = explode("@", $this->value);

    $domain = $emailParts[1];
    // J'accède ici à la constante de la classe avec self::BLACKLIST
    return in_array($domain, self::BLACKLIST);
  }
```

> La manière d'accéder à une constante de classe diffère de l'accès à un attribut. Pour accéder à un attribut, on va utiliser une flèche `->` précédée du mot-clé `$this`. Pour accéder à une constante, on utilisera `self::MA_CONSTANTE`

### Exceptions

Les exceptions permettent d'effectuer une **gestion d'erreurs** dans le cadre d'une programmation orientée objet.

En effet, quand on conçoit une fonctionnalité, on va prévoir un flot d'exécution normal. Mais il est possible que surviennent des situations indésirables. On peut alors prévoir leur arrivée en tant qu'elles constituent une **exception** au comportement prévu.

Ainsi, nous pouvons **lancer** une exception au code appelant, qui sera chargé de l'**attraper**.

C'est ici qu'interviendra la gestion de l'erreur : si on attrape une exception, alors nous pouvons gérer son arrivée et agir en conséquence.

#### Exemple

Dans une classe `Email`, à laquelle je fournis l'email à stocker en attribut lors de la construction, je souhaite empêcher l'instanciation de la classe si la valeur passée est incorrecte (email mal formaté).

Ce comportement aurait une double utilité : non seulement je détecterai l'erreur au plus tôt (dès la construction de l'objet), mais en empêchant son instanciation, j'empêche donc également le code qui a appelé ce constructeur de disposer d'une instance de classe dans un état instable (avec un email incorrect, mon instance d'`Email` n'est pas saine).

Ainsi :

```php
class Email
{
  private string $email;

  /**
   * Creates a new Email instance
   *
   * @param string $email The value to be stored in instance
   * @throws InvalidArgumentException if email format is not valid
   */
  public function __construct(string $email)
  {
    $this->email = $email;

    if (!$this->isValid()) {
      throw new InvalidArgumentException("Le format de l'adresse email est invalide");
    }
  }

  public function isValid(): bool
  {
    return filter_var($this->email, FILTER_VALIDATE_EMAIL) !== false;
  }

  //...
}
```

Et dans un fichier qui aurait besoin d'une instance de la classe `Email` :

```php
try {
  $email = new Email($_POST['email']);
} catch (InvalidArgumentException $ex) {
  echo $ex->getMessage();
  exit;
}
```

Ceci nous évite de faire quelque chose comme :

```php
$email = new Email($_POST['email']);
// A ce moment, on dispose d'une instance dans un état invalide.
// Donc, nous sommes obligés d'appeler isValid pour valider son état avant d'envisager
// quoi que ce soit d'autre.
// C'est exactement le problème : on risque d'oublier d'appeler systématiquement
// cette méthode pour vérifier l'intégrité de notre objet, et donc
// manipuler un objet invalide ==> source d'erreurs non maîtrisées
if (!$email->isValid()) {
  echo "Le format de l'adresse email est invalide";
  exit;
}
```

> Les exceptions permettent donc une détection plus rapide et plus claire des comportements non désirés

## L'héritage

Il peut arriver qu'on veuille définir plusieurs types partageant seulement quelques attributs, mais pas tous.

Dans ce cas, regrouper tous ces attributs dans une seule classe impliquerait que toute instance de cette classe possèderait des attributs inutiles.

Dans le but d'éviter au maximum cette situation, il est possible de définir des classes **héritant** d'une autre classe.

Par exemple, pour notre produit : si je disposais de produits rectangulaires, avec une hauteur et une largeur, et de produits circulaires, possédant eux un diamètre, alors je peux deviner que quand j'aurai un produit rectangulaire le diamètre me sera inutile, et vice versa.

Définissons une nouvelle classe `ProduitRect` avec une largeur et une hauteur, héritant de la classe `Produit` :

```php
class ProduitRect extends Produit
{
  private $largeur;
  private $hauteur;

  // Accesseurs & modificateurs de largeur et hauteur non inscrits ici pour faire plus court
}
```

Je peux à présent instancier un objet de type `ProduitRect` :

```php
$monProduitRectangulaire = new ProduitRect();
```

Mon produit rectangulaire aura automatiquement le nom "Téléviseur" car il **hérite** du constructeur de `Produit`.

Je peux cependant définir un constructeur dans la classe `ProduitRect`, qui viendra **surcharger** le constructeur parent : le remplacer. Alors, pour ne pas perdre l'initialisation du nom de produit, effectuée dans la classe parente `Produit`, je peux appeler le constructeur parent depuis le constructeur enfant :

```php
class ProduitRect extends Produit
{
  private $largeur;
  private $hauteur;

  public function __construct(int $l, int $h)
  {
    parent::__construct(); // Ici, j'appelle le constructeur parent
    $this->largeur = $l;
    $this->hauteur = $h;
  }
}
```

Si je voulais toujours pouvoir définir le nom de mon produit via le constructeur, je pourrais alors transférer un paramètre `$nom` au parent :

```php
class ProduitRect extends Produit
{
  private $largeur;
  private $hauteur;

  public function __construct(int $l, int $h, string $nom = "Téléviseur")
  {
    parent::__construct($nom); // Ici, j'appelle le constructeur parent
    $this->largeur = $l;
    $this->hauteur = $h;
  }
}
```

> Une classe ne peut hériter que d'une et une seule classe. L'héritage multiple n'est pas possible

### Portées et héritage

Nous avons évoqué précédemment la portée `protected` pour un attribut ou une méthode. L'intérêt des portées se trouve dans ce qu'on souhaite exposer à l'extérieur d'une classe. L'extérieur d'une classe inclut donc le code qui va instancier des objets de cette classe, mais aussi les éventuelles classes héritant d'une classe.

Dans notre exemple, la classe `ProduitRect` ne peut pas accéder directement aux attributs `$nom` et `$prix` car ils sont définis comme `private`.

Si on voulait pouvoir accéder à ces attributs définis dans `Produit` depuis la classe enfant `ProduitRect`, il faudrait alors les rendre `protected` :

```php
class Produit
{
  protected $nom;
  protected $prix;
  //...
}

//...

class ProduitRect extends Produit
{
  //...

  public function displayInfos()
  {
    // On peut ici utiliser $this->nom car $nom est protected dans Produit
    echo $this->nom . ", rectangulaire : L" . $this->largeur . ", H" . $this->hauteur;
  }
```

Pour résumer les portées des attributs, méthodes ou constantes :

- `public` : accessible n'importe où par n'importe qui
- `protected` : inaccessible par un code instanciant un objet de cette classe, mais accessible par les classes héritant de cette classe
- `private` : inaccessible par tout code extérieur, enfants inclus. Manipulable uniquement dans la classe courante

## Classes abstraites

Avec le mot-clé `abstract`, placé avant le mot-clé `class`, il est possible de rendre une classe **abstraite**.

Quel est l'intérêt d'une classe abstraite ?

- Une classe abstraite ne peut pas être instanciée. Ainsi, si on veut définir une structure de classe réellement **de base**, qui sera héritée ensuite, mais dont on ne veut pas manipuler d'instances de classes, alors on peut la rendre abstraite. Dans notre exemple, on définit une classe abstraite `AbstractProduit`, en tant qu'abstraction, avec des attributs généraux. Mais les produits dont on souhaite manipuler des instances sont les `ProduitRect` ou les `ProduitCirc`

- Dans une classe abstraite, il est possible de définir une ou plusieurs **méthodes abstraites**. Une méthode abstraite ne définir aucun corps de méthode (aucune implémentation). Ainsi, le fait de définir une méthode abstraite **force les classes enfants à fournir une implémentation de cette méthode**

```php
abstract class AbstractProduit
{
  protected $nom;
  // ...

  // On définit une méthode qui devra être implémentée par toutes les classes enfants
  public abstract function getSurface(): float;
}

class ProduitRect extends AbstractProduit
{
  private $largeur;
  private $hauteur;
  //...

  // On fournit une implémentation de la méthode getSurface
  public function getSurface(): float
  {
    return $this->largeur * $this->hauteur;
  }
}
```

## Polymorphisme

La définition d'une classe parente, abstraite ou non, ouvre la voie à des comportements **polymorphiques**.

Reprenons l'exemple des produits :

On sait que, dans notre application, on peut se retrouver avec des instances de `ProduitRect` ou bien de `ProduitCirc`. En réalité, ces instances sont également de **type** `AbstractProduit`, puisque ces classes héritent d'`AbstractProduit`. Oui, concrètement, il s'agit de `ProduitRect` ou de `ProduitCirc`. Mais le fait est que si on le souhaite, on peut les considérer en tant qu'`AbstractProduit`.

Ainsi, si je dispose par exemple d'une fonction `getProduits` renvoyant une liste de produits contenant des rectangulaires et des circulaires, alors je ne peux prévoir précisément de quelle quantité de produits de chaque type je disposerai, ni dans quel ordre ils vont arriver.

Je peux alors décider de les considérer comme des `AbstractProduit` :

```php
/**
 * Gets all products
 *
 * @return AbstractProduit[]
 */
function getProduits(): array
{
  $produitRect = new ProduitRect(600, 800);
  $produitCirc = new ProduitCirc();

  return [$produitRect, $produitCirc];
}
```

> La PHPDoc `@return` permet à votre IDE de déterminer précisément que cette fonction va retourner un tableau d'`AbstractProduit`, puisque dans la signature de la fonction, on ne peut pas mettre directement `AbstractProduit[]` mais seulement `array`

Et à l'utilisation :

```php
$mesProduits = getProduits();

foreach ($mesProduits as $monProduit) {
  // Quel que soit le type (rect ou circ) de l'objet, on sait que vu qu'il hérite de la classe AbstractProduit, alors il est obligatoire qu'il fournisse une implémentation de cette méthode
  echo $monProduit->getSurface();
}
```

Le comportement sera donc polymorphique, puisqu'en fonction du type, on aura une exécution différente de `getSurface`.

## Interfaces

Il est également possible de définir des abstractions à l'aide des **interfaces**.

Les interfaces sont quant à elles concentrées uniquement sur la définition de **prototypes de méthodes publiques**. Une classe peut ensuite **implémenter** une ou plusieurs interfaces. Dans ce cas, elle est obligée de fournir une implémentation de toutes les méthodes définies dans l'interface.

> On appelle communément une interface un **contrat d'implémentation**. Cela consiste simplement à dire : "si cette classe implémente cette interface, alors elle doit fournir une implémentation de toutes les méthodes définies dans l'interface", comme un contrat à respecter

Par exemple :

```php
interface IDisplayable
{

  public function display(): void;
}

class ProduitRect implements IDisplayable
{
  //...

  public function display(): void
  {
    echo "Je suis un produit rectangulaire";
  }
}

class ProduitCirc implements IDisplayable
{
  //...

  public function display(): void
  {
    echo "Je suis un produit circulaire";
  }
}
```

Ainsi, tout comme nous l'avons vu précédemment concernant les classes abstraites, il est possible de considérer des instances de `ProduitRect` ou `ProduitCirc` comme des types `IDisplayable`.

Quel que soit le type concret de l'objet, si on le considère comme un `IDisplayable` alors on sait qu'il doit respecter le contrat et qu'on peut donc appeler la méthode `display` sur cet objet.

Nous aurons donc également un comportement polymorphique.
