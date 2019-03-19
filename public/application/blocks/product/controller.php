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
		return t('Basic Test');
	}

	public function getBlockTypeDescription()
    {
		return t('A simple testing block for developers');
	}

    public function view()
    {
        $db = \Database::connection();
        $row = $db->fetchAssoc('select * from btProducts where bID = ?', [$this->bID]);
        $this->set('title', $row['btTitle']);
        $this->set('description', $row['btDescription']);
        $this->set('price', $row['btPrice']);
        $this->set('image', $row['btImage']);
    }

    public function edit()
    {
        $db = \Database::connection();
        $row = $db->fetchAssoc('select * from btProducts where bID = ?', [$this->bID]);
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
            $target_dir = "img/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $image_file_type = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            $extensions_arr = array("png", "gif", "jpeg", "jpg");
            if (in_array($image_file_type, $extensions_arr)) {
                $db = Loader::db();
                $sql = "INSERT INTO btProducts(bID, btTitle, btDescription, btPrice, btImage) VALUES (?, ?, ?, ?, ?)";
                $args = array(
                    $this->bID,
                    $this->post('title'),
                    $this->post('description'),
                    $this->post('price'),
                    $unique . "." . $image_file_type
                );
                $db->Execute($sql, $args);

                if (file_exists($target_dir . $name)) {
                    rename($target_dir . $name, $target_dir . $unique . "." . $image_file_type);
                } else {
                    move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $name);
                    rename($target_dir . $name, $target_dir . $unique . "." . $image_file_type);
                }
            }

            if (!isset($_FILES["image"]["name"]) && empty($_FILES["image"]["name"])) {
                $db = Loader::db();
                $sql = "INSERT INTO btProducts(bID, btTitle, btDescription, btPrice) VALUES (?, ?, ?, ?)";
                $args = array(
                    $this->bID,
                    $this->post('title'),
                    $this->post('description'),
                    $this->post('price'),
                );
                $db->Execute($sql, $args);
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
     * @var \Concrete\Core\Database\ $db
     */
    public function delete()
    {
        parent::delete();
        $conn = \Database::connection();
        $qb = $conn->createQueryBuilder()
            ->delete('btProducts')
            ->where('bID = :block_id')
            ->setParameter(':block_id', $this->bID);
        $qb->execute();
    }
}
