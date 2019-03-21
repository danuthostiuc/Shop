<?php //defined('C5_EXECUTE') or die(_("Access Denied."));

namespace Application\Block\Product;
use Concrete\Core\Block\BlockController;
use Concrete\Core\Legacy\Loader;

class Controller extends BlockController
{
	protected $btTable = "btProducts";
	protected $btTitle = "btTitle";
	protected $btDescription = "btDescription";
	protected $btPrice = "btPrice";
	protected $btImage = "btImage";
	protected $btInterfaceWidth = "350";
	protected $btInterfaceHeight = "300";

	public function getBlockTypeName()
    {
		return t('Product');
	}

	public function getBlockTypeDescription()
    {
		return t('A basic block for products');
	}

    public function view()
    {
        $conn = \Database::connection();
        $row = $conn->fetchAssoc('select * from btProducts where bID = ?', [$this->bID]);
        $c = \Page::getCurrentPage();
        $this->set('c', $c);
        $this->set('id', $row['btID']);
        $this->set('title', $row['btTitle']);
        $this->set('description', $row['btDescription']);
        $this->set('price', $row['btPrice']);
        $this->set('image', $row['btImage']);
    }

    public function edit()
    {
        $conn = \Database::connection();
        $row = $conn->fetchAssoc('select * from btProducts where bID = ?', [$this->bID]);
        $this->set('id', $row['bID']);
        $this->set('title', $row['btTitle']);
        $this->set('description', $row['btDescription']);
        $this->set('price', $row['btPrice']);
        $this->set('image', $row['btImage']);
    }

    public function save($data)
    {
        if (isset($_FILES["image"]["name"]) && !empty($_FILES["image"]["name"])) {
            $unique = uniqid();
            $name = $_FILES["image"]["name"];
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/application/files/img/';
            //$target_file = $target_dir . basename($_FILES["image"]["name"]);
            $image_file_type = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            $extensions_arr = array("png", "gif", "jpeg", "jpg");
            if (in_array($image_file_type, $extensions_arr)) {
                $conn = Loader::db();
                $sql = "INSERT INTO btProducts(bID, btTitle, btDescription, btPrice, btImage) VALUES (?, ?, ?, ?, ?)";
                $args = array(
                    $this->bID,
                    $this->post('title'),
                    $this->post('description'),
                    $this->post('price'),
                    $unique . "." . $image_file_type
                );
                $conn->Execute($sql, $args);

                if (file_exists($target_dir . $name)) {
                    rename($target_dir . $name, $target_dir . $unique . "." . $image_file_type);
                } else {
                    move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $name);
                    rename($target_dir . $name, $target_dir . $unique . "." . $image_file_type);
                }
            }

            if (!isset($_FILES["image"]["name"]) && empty($_FILES["image"]["name"])) {
                $conn = Loader::db();
                $sql = "INSERT INTO btProducts(bID, btTitle, btDescription, btPrice) VALUES (?, ?, ?, ?)";
                $args = array(
                    $this->bID,
                    $this->post('title'),
                    $this->post('description'),
                    $this->post('price'),
                );
                $conn->Execute($sql, $args);
            }
        }

//        $conn = \Database::connection();
//        $qb = $conn->createQueryBuilder()
//            ->insert('btProducts')
//            ->values(array(
//                'bID' => '?',
//                'btTitle' => '?',
//                'btDescription' => '?',
//                'btPrice' => '?'
//            ));
//        $args = array(
//            $this->bID,
//            $this->post('title'),
//            $this->post('description'),
//            $this->post('price'),
//        );
//        $qb->execute($args);
    }

    /**
     * @var \Concrete\Core\Database\ $conn
     */
    public function delete()
    {
        parent::delete();

        $conn = \Database::connection();
        $row = $conn->fetchAssoc('select btImage from btProducts where bID = ?', [$this->bID]);

        $conn = \Database::connection();
        $qb = $conn->createQueryBuilder()
            ->delete('btProducts')
            ->where('bID = :block_id')
            ->setParameter(':block_id', $this->bID);
        $qb->execute();

        if (file_exists('/application/files/img/' . $row['btImage'])) {
            unlink('/application/files/img/' . $row['btImage']);
        }
    }
}
