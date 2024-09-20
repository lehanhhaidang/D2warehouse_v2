<?

namespace App\Repositories;

use App\Models\Material;
use App\Repositories\Interface\MaterialRepositoryInterface;

class MaterialRepository implements MaterialRepositoryInterface
{
    public function all()
    {
        return Material::all();
    }

    public function find($id)
    {
        return Material::find($id);
    }

    public function create(array $data)
    {
        return Material::create($data);
    }

    public function update($id, array $data)
    {
        $material = Material::find($id);
        if ($material) {
            $material->update($data);
            return $material;
        }
        return null;
    }

    public function delete($id)
    {
        $material = Material::find($id);
        if ($material) {
            $material->delete();
            return true;
        }
        return false;
    }
}
