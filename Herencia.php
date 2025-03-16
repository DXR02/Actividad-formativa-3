
<?php
declare(strict_types=1);

namespace App\Models;

use InvalidArgumentException;
use Exception;

/**
 * Clase base Producto.
 * Representa un producto genérico en el inventario.
 */
class Producto
{
    /**
     * @var int $id Identificador único del producto
     */
    protected int $id;

    /**
     * @var string $nombre Nombre del producto
     */
    protected string $nombre;

    /**
     * @var float $precio Precio unitario del producto
     */
    protected float $precio;

    /**
     * @var int $cantidad Cantidad en inventario del producto
     */
    protected int $cantidad;

    /**
     * Constructor de la clase Producto.
     *
     * @param int    $id       ID del producto
     * @param string $nombre   Nombre del producto
     * @param float  $precio   Precio del producto
     * @param int    $cantidad Cantidad en inventario
     *
     * @throws InvalidArgumentException si alguno de los argumentos es inválido
     */
    public function __construct(int $id, string $nombre, float $precio, int $cantidad)
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("El ID debe ser un número positivo.");
        }
        if ($precio < 0) {
            throw new InvalidArgumentException("El precio no puede ser negativo.");
        }
        if ($cantidad < 0) {
            throw new InvalidArgumentException("La cantidad no puede ser negativa.");
        }

        $this->id = $id;
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->cantidad = $cantidad;
    }

    /**
     * Obtiene la información básica del producto en formato de cadena.
     *
     * @return string
     */
    public function obtenerInformacion(): string
    {
        return sprintf(
            "ID: %d | Producto: %s | Precio: $%.2f | Cantidad: %d",
            $this->id,
            $this->nombre,
            $this->precio,
            $this->cantidad
        );
    }
}

/**
 * Clase derivada Electronico.
 * Hereda de Producto y agrega atributos y métodos exclusivos de electrónicos.
 */
class Electronico extends Producto
{
    /**
     * @var string $marca Marca del producto electrónico
     */
    private string $marca;

    /**
     * @var string $voltaje Voltaje requerido por el producto electrónico
     */
    private string $voltaje;

    /**
     * Constructor de la clase Electronico.
     * Llama al constructor de la clase base para reutilizar sus atributos
     * y define los propios de la subclase.
     *
     * @param int    $id       ID del producto
     * @param string $nombre   Nombre del producto
     * @param float  $precio   Precio del producto
     * @param int    $cantidad Cantidad en inventario
     * @param string $marca    Marca del electrónico
     * @param string $voltaje  Voltaje requerido
     */
    public function __construct(
        int $id,
        string $nombre,
        float $precio,
        int $cantidad,
        string $marca,
        string $voltaje
    ) {
        parent::__construct($id, $nombre, $precio, $cantidad);
        $this->marca = $marca;
        $this->voltaje = $voltaje;
    }

    /**
     * Sobrescribe el método obtenerInformacion() de la clase base
     * para incluir la marca y el voltaje.
     *
     * @return string
     */
    public function obtenerInformacion(): string
    {
        // Llama al método de la clase base y le agrega información adicional
        $infoBase = parent::obtenerInformacion();
        return $infoBase . " | Marca: {$this->marca} | Voltaje: {$this->voltaje}";
    }

    /**
     * Método exclusivo de la clase Electronico.
     * Devuelve una supuesta garantía de 1 año para el producto electrónico.
     *
     * @return string
     */
    public function obtenerGarantia(): string
    {
        return "Este producto electrónico tiene una garantía de 1 año.";
    }
}

/**
 * Clase derivada Libro.
 * Hereda de Producto y agrega atributos y métodos exclusivos de libros.
 */
class Libro extends Producto
{
    /**
     * @var string $autor Autor del libro
     */
    private string $autor;

    /**
     * @var int $numeroPaginas Número de páginas del libro
     */
    private int $numeroPaginas;

    /**
     * Constructor de la clase Libro.
     * Llama al constructor de la clase base para reutilizar sus atributos
     * y define los propios de la subclase.
     *
     * @param int    $id           ID del producto
     * @param string $nombre       Nombre del producto
     * @param float  $precio       Precio del producto
     * @param int    $cantidad     Cantidad en inventario
     * @param string $autor        Autor del libro
     * @param int    $numeroPaginas Número de páginas
     */
    public function __construct(
        int $id,
        string $nombre,
        float $precio,
        int $cantidad,
        string $autor,
        int $numeroPaginas
    ) {
        parent::__construct($id, $nombre, $precio, $cantidad);
        $this->autor = $autor;
        $this->numeroPaginas = $numeroPaginas;
    }

    /**
     * Sobrescribe el método obtenerInformacion() para agregar los datos
     * exclusivos de un libro (autor y número de páginas).
     *
     * @return string
     */
    public function obtenerInformacion(): string
    {
        $infoBase = parent::obtenerInformacion();
        return $infoBase . " | Autor: {$this->autor} | Páginas: {$this->numeroPaginas}";
    }

    /**
     * Método exclusivo de la clase Libro.
     * Devuelve una recomendación de lectura basada en el contenido.
     *
     * @return string
     */
    public function recomendacionDeLectura(): string
    {
        return "Este libro es altamente recomendado para los amantes de la literatura.";
    }
}

/**
 * Clase Inventario.
 * Gestiona múltiples productos en una colección.
 */
class Inventario
{
    /**
     * @var array<int, Producto> $productos
     * Almacena los productos en un array asociativo, clave = ID del producto
     */
    private array $productos = [];

    /**
     * Agrega un nuevo producto al inventario.
     *
     * @param Producto $producto
     * @throws Exception si el ID del producto ya existe en el inventario
     */
    public function agregarProducto(Producto $producto): void
    {
        if (isset($this->productos[$producto->id])) {
            // Se lanza una excepción si ya hay un producto con el mismo ID
            throw new Exception("Ya existe un producto con el ID {$producto->id}.");
        }
        $this->productos[$producto->id] = $producto;
        echo "Producto agregado: {$producto->obtenerInformacion()}\n";
    }

    /**
     * Lista todos los productos en el inventario.
     *
     * @return void
     */
    public function listarProductos(): void
    {
        if (empty($this->productos)) {
            echo "No hay productos en el inventario.\n";
            return;
        }

        echo "\nInventario Actual:\n";
        echo "---------------------------------\n";
        foreach ($this->productos as $producto) {
            echo $producto->obtenerInformacion() . "\n";
        }
    }

    /**
     * Busca un producto por su ID.
     *
     * @param int $id
     * @return Producto|null
     */
    public function buscarProductoPorId(int $id): ?Producto
    {
        return $this->productos[$id] ?? null;
    }

    /**
     * Elimina un producto del inventario por su ID.
     *
     * @param int $id
     * @return bool Indica si se eliminó el producto o no
     */
    public function eliminarProducto(int $id): bool
    {
        if (isset($this->productos[$id])) {
            unset($this->productos[$id]);
            return true;
        }
        return false;
    }

    /**
     * Calcula el valor total del inventario sumando
     * (precio * cantidad) de cada producto.
     *
     * @return float
     */
    public function calcularValorTotal(): float
    {
        $total = 0;
        foreach ($this->productos as $producto) {
            $total += $producto->precio * $producto->cantidad;
        }
        return $total;
    }
}

// -----------------------------------------------------
// Programa principal - Pruebas de la Herencia
// -----------------------------------------------------

try {
    // Crear instancias de la subclase Electronico
    $producto1 = new Electronico(1, "Laptop HP", 899.99, 5, "HP", "110V");
    $producto2 = new Electronico(2, "Smartphone Samsung", 699.99, 10, "Samsung", "220V");

    // Crear instancia de la subclase Libro
    $producto3 = new Libro(3, "Cien años de soledad", 29.99, 15, "Gabriel García Márquez", 496);

    // Crear instancia de la clase base
    $producto4 = new Producto(4, "Teclado genérico", 12.50, 30);

    // Instanciar inventario
    $inventario = new Inventario();

    // Agregar productos al inventario
    $inventario->agregarProducto($producto1);
    $inventario->agregarProducto($producto2);
    $inventario->agregarProducto($producto3);
    $inventario->agregarProducto($producto4);

    // Listar productos para verificar su información
    $inventario->listarProductos();

    // Llamar a método exclusivo de Electronico
    echo "\nGarantía del producto 1: " . $producto1->obtenerGarantia() . "\n";

    // Llamar a método exclusivo de Libro
    echo "Recomendación de lectura del producto 3: " . $producto3->recomendacionDeLectura() . "\n";

    // Calcular el valor total del inventario
    echo "\nValor total del inventario: $"
         . number_format($inventario->calcularValorTotal(), 2)
         . "\n";

    // Buscar un producto por ID
    $buscado = $inventario->buscarProductoPorId(3);
    if ($buscado) {
        echo "\nProducto con ID 3 encontrado: " . $buscado->obtenerInformacion() . "\n";
    }

    // Eliminar un producto
    $inventario->eliminarProducto(2);
    echo "\nDespués de eliminar el producto con ID 2:\n";
    $inventario->listarProductos();

} catch (Exception $e) {
    // Manejo de excepciones
    echo "Error: " . $e->getMessage() . "\n";
}
