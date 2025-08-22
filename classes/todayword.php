<?php
class TodayWord extends Dbh
{

    function createTodaysWord(string $word, string $body, int $school_id, ?int $class_id = null, ?int $group_type = null, ?string $show_date = null, int $is_active = 1, ?string $imgName = null): int
    {
        $db = $this->connect();
        $stmt = $db->prepare("
        INSERT INTO todays_word (word, body, school_id, class_id, group_type, show_date, is_active, image)
        VALUES (:word, :body, :school_id, :class_id, :group_type, :show_date, :is_active, :image)
    ");
        $stmt->execute([
            ':word' => $word,
            ':body' => $body,
            ':school_id' => $school_id,
            ':class_id' => $class_id,
            ':group_type' => $group_type,
            ':show_date' => $show_date,
            ':is_active' => $is_active,
            ':image' => $imgName
        ]);

        return (int) $db->lastInsertId();
    }

    function getTodaysWordById(int $id): ?array
    {
        $db = $this->connect();

        $stmt = $db->prepare("SELECT * FROM todays_word WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    function updateTodaysWord(int $id, string $word, string $body, int $school_id, ?int $class_id, ?string $show_date = null, int $is_active = 1): bool
    {
        $db = $this->connect();

        $stmt = $db->prepare("
        UPDATE todays_word
        SET word = :word,
            body = :body,
            school_id = :school_id,
            class_id = :class_id,
            show_date = :show_date,
            is_active = :is_active
        WHERE id = :id
    ");
        return $stmt->execute([
            ':word' => $word,
            ':body' => $body,
            ':school_id' => $school_id,
            ':class_id' => $class_id,
            ':show_date' => $show_date,
            ':is_active' => $is_active,
            ':id' => $id
        ]);
    }

    function deleteTodaysWord(int $id): bool
    {
        $db = $this->connect();

        $stmt = $db->prepare("DELETE FROM todays_word WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    function getAllTodaysWords(?int $school_id = null, ?int $class_id = null, ?string $date = null): array
    {
        $db = $this->connect();

        $query = "
        SELECT
        todays_word.*, 
        classes_lnp.name AS class_name, 
        schools_lnp.name AS school_name
        FROM todays_word
        LEFT JOIN classes_lnp ON todays_word.class_id = classes_lnp.id
        LEFT JOIN schools_lnp ON todays_word.school_id = schools_lnp.id
        ";
        $params = [];

        if ($school_id !== null) {
            $query .= " AND school_id = :school_id";
            $params[':school_id'] = $school_id;
        }

        if ($class_id !== null) {
            $query .= " AND class_id = :class_id";
            $params[':class_id'] = $class_id;
        }

        if ($date !== null) {
            $query .= " AND show_date = :show_date";
            $params[':show_date'] = $date;
        }

        $stmt = $db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getAllTodaysWordsList()
    {

        $announcementInfo = $this->getAllTodaysWords();
        $dateFormat = new DateFormat();

        foreach ($announcementInfo as $key => $value) {


            $active_status = '<span class="badge badge-light-success">Aktif</span>';
            if (!$value['is_active']) {
                $active_status = '<span class="badge badge-light-danger">Pasif</span>';
            }
            $date = $value['show_date'] ? $dateFormat->changeDate($value['show_date']) : '-';

            $alter_button = $value['is_active'] ? "Pasif Yap" : "Aktif Yap";
            $wordsList = '
                    <tr id="' . $value['id'] . '">
                        <td>
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="1" />
                            </div>
                        </td>
                        <td>
                            <a class="cursor-pointer symbol symbol-90px symbol-lg-90px"><img src="assets/media/today-word/' . $value['image'] . '"></a>
                        </td>
                        <td>
                            <a class="text-gray-800 text-hover-primary mb-1">' . $value['word'] . '</a>
                        </td>
                        <td>
                        ' . (strlen($value['body']) > 40 ? substr($value['body'], 0, 40) . '...' : $value['body']) . '
                        </td>

                        <td>' . $value['class_name'] . '</td>
                        <td>' . $value['school_name'] . '</td>
                        <td>' . $date . '</td>

                        <td>
                            <span class="symbol symbol-10px me-2">

                            ' . $active_status . '
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">İşlemler
                                <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-customer-table-filter="delete_row">' . $alter_button . '</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                ';
            echo $wordsList;
        }
    }

    public function updateWordStatus($wordId, $isActive)
    {
        $db = $this->connect();


        try {
            $stmt = $db->prepare("
            UPDATE todays_word
            SET is_active = :is_active
            WHERE id = :word_id
        ");

            return $stmt->execute([
                ':is_active' => $isActive,
                ':word_id' => $wordId
            ]);
        } catch (Exception $e) {
            return false;
        }
    }
    public function updateWordStatusArray($wordIds, $isActive)
    {
        $db = $this->connect();

        if (empty($wordIds)) {
            return false;
        }

        $placeholders = implode(',', array_fill(0, count($wordIds), '?'));

        try {
            $stmt = $db->prepare("
            UPDATE todays_word
            SET is_active = ?
            WHERE id IN ($placeholders)
        ");

            $params = array_merge([$isActive], $wordIds);

            return $stmt->execute($params);
        } catch (Exception $e) {
            return false;
        }
    }


    function getTodaysOrRandomWord(int $school_id, ?int $class_id): ?array
    {

        $db = $this->connect();

        $queryToday = "
        SELECT *
        FROM todays_word d
        LEFT JOIN classes_lnp c ON c.id = :class_id
        WHERE d.is_active = 1
            AND d.school_id = :school_id
            AND (d.class_id = :class_id OR d.class_id IS NULL)
            AND d.show_date = CURDATE()
            AND (d.group_type = c.class_type OR d.group_type IS NULL)
        LIMIT 1;
        ";

        $stmt = $db->prepare($queryToday);
        $stmt->execute([
            ':school_id' => $school_id,
            ':class_id' => $class_id,
        ]);

        $todayWord = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($todayWord) {
            return $todayWord;
        }

        $queryRandom = "
        SELECT * FROM todays_word d
        LEFT JOIN classes_lnp c ON c.id = :class_id
        WHERE is_active = 1
            AND d.school_id = :school_id
            AND (d.class_id = :class_id OR d.class_id IS NULL)
            AND (d.group_type = c.class_type OR d.group_type IS NULL)
        ORDER BY RAND()
        LIMIT 1
        ";

        $stmt = $db->prepare($queryRandom);
        $stmt->execute([
            ':school_id' => $school_id,
            ':class_id' => $class_id,
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

}