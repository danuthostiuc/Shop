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
        //var_dump_safe($row);
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

    public function delete()
    {
        parent::delete();
    }
}
