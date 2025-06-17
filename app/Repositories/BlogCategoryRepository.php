<?php

namespace App\Repositories;

use App\Models\BlogCategory as Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class BlogCategoryRepository.
 */
class BlogCategoryRepository extends CoreRepository
{
    /**
     * Повертає повне ім'я класу моделі, з якою працює репозиторій.
     *
     * @return string
     */
    protected function getModelClass()
    {
        return Model::class; //абстрагування моделі BlogCategory, для легшого створення іншого репозиторія
    }

    /**
     * Отримати модель для редагування в адмінці за ID.
     *
     * @param int $id
     * @return Model
     */
    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }

    /**
     * Отримати список категорій для виводу в випадаючий список.
     *
     * @return Collection
     */
    public function getForComboBox()
    {
        // return $this->startConditions()->all(); // Закоментований оригінальний рядок

        $columns = implode(', ', [
            'id',
            'CONCAT (id, ". ", title) AS id_title', //додаємо поле id_title
        ]);

        /*
        // 1 варіант (закоментований, як у завданні)
        $result = $this
            ->startConditions()
            ->select('blog_categories.*',
                \DB::raw('CONCAT (id, ". ", title) AS id_title'))
            ->toBase() //не робити колекцію(масив) BlogCategory, отримати дані у вигляді класу
            ->get();
        */

        // 2 варіант (використовуємо selectRaw)
        $result = $this
            ->startConditions()
            ->selectRaw($columns) // Використовуємо selectRaw для сирого SQL-виразу
            ->toBase() // Повертаємо базову колекцію Laravel, а не Eloquent моделі
            ->get();

        //dd($result); // Закоментуйте або видаліть після перевірки

        return $result;
    }
    public function getAllWithPaginate($perPage = null)
    {
        $columns = ['id', 'title', 'parent_id'];

        $result = $this
            ->startConditions()
            ->select($columns)
            ->with(['parentCategory:id,title']) // Додано жадібне завантаження для parentCategory
            ->paginate($perPage);

        return $result;
    }
}
