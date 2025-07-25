<?php
class DoYouKnow extends Dbh
{

    function createDoYouKnow(string $body, int $school_id, ?int $class_id = null, ?string $show_date = null, int $is_active = 1): int
    {
        $db = $this->connect();
        $stmt = $db->prepare("
        INSERT INTO doyouknow (body, school_id, class_id, show_date, is_active)
        VALUES (:body, :school_id, :class_id, :show_date, :is_active)
    ");
        $stmt->execute([
            ':body' => $body,
            ':school_id' => $school_id,
            ':class_id' => $class_id,
            ':show_date' => $show_date,
            ':is_active' => $is_active
        ]);

        return (int) $db->lastInsertId();
    }

    function getDoYouKnowById(int $id): ?array
    {
        $db = $this->connect();

        $stmt = $db->prepare("SELECT * FROM doyouknow WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    function updateDoYouKnow(int $id, string $body, int $school_id, ?int $class_id, ?string $show_date = null, int $is_active = 1): bool
    {
        $db = $this->connect();

        $stmt = $db->prepare("
        UPDATE doyouknow
        SET
            body = :body,
            school_id = :school_id,
            class_id = :class_id,
            show_date = :show_date,
            is_active = :is_active
        WHERE id = :id
    ");
        return $stmt->execute([

            ':body' => $body,
            ':school_id' => $school_id,
            ':class_id' => $class_id,
            ':show_date' => $show_date,
            ':is_active' => $is_active,
            ':id' => $id
        ]);
    }

    function deleteDoYouKnow(int $id): bool
    {
        $db = $this->connect();

        $stmt = $db->prepare("DELETE FROM doyouknow WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    function getAllDoYouKnows(?int $school_id = null, ?int $class_id = null, ?string $date = null): array
    {
        $db = $this->connect();

        $query = "
        SELECT 
            doyouknow.*, 
            classes_lnp.name AS class_name, 
            schools_lnp.name AS school_name
        FROM doyouknow
        LEFT JOIN classes_lnp ON doyouknow.class_id = classes_lnp.id
        LEFT JOIN schools_lnp ON doyouknow.school_id = schools_lnp.id
        WHERE 1
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


    public function getAllDoYouKnowsList()
    {

        $announcementInfo = $this->getAllDoYouKnows();
        $dateFormat = new DateFormat();

        foreach ($announcementInfo as $key => $value) {


            $active_status = '<span class="badge badge-light-success">Aktif</span>';
            if (!$value['is_active']) {
                $active_status = '<span class="badge badge-light-danger">Pasif</span>';
            }
            $date = $value['show_date'] ? $dateFormat->changeDate($value['show_date']) : '-';

            $alter_button = $value['is_active'] ? "Pasif Yap" : "Aktif Yap";
            $knowsList = '
                    <tr id="' . $value['id'] . '">
                        <td>
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="1" />
                            </div>
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
            echo $knowsList;
        }
    }

    public function updateKnowStatus($knowId, $isActive)
    {
        $db = $this->connect();


        try {
            $stmt = $db->prepare("
            UPDATE doyouknow
            SET is_active = :is_active
            WHERE id = :know_id
        ");

            return $stmt->execute([
                ':is_active' => $isActive,
                ':know_id' => $knowId
            ]);
        } catch (Exception $e) {
            return false;
        }
    }
    public function updatKnowStatusArray($knowIds, $isActive)
    {
        $db = $this->connect();

        if (empty($knowIds)) {
            return false;
        }

        $placeholders = implode(',', array_fill(0, count($knowIds), '?'));

        try {
            $stmt = $db->prepare("
            UPDATE doyouknow
            SET is_active = ?
            WHERE id IN ($placeholders)
        ");

            $params = array_merge([$isActive], $knowIds);

            return $stmt->execute($params);
        } catch (Exception $e) {
            return false;
        }
    }


    function getTodaysOrRandomKnow(int $school_id, ?int $class_id): ?array
    {

        $db = $this->connect();

        $queryToday = "
        SELECT * FROM doyouknow
        WHERE is_active = 1
          AND school_id = :school_id
          AND (class_id = :class_id OR class_id IS NULL)
          AND show_date = CURDATE()
        LIMIT 1
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
        SELECT * FROM doyouknow
        WHERE is_active = 1
          AND school_id = :school_id
          AND (class_id = :class_id OR class_id IS NULL)
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