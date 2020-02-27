<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Location extends Model {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    function get_states( $args = array() ) {

        $check = false;
        $params = array();
        $where = '';
        $order = " ORDER BY state_name ASC";
        $limit = '';

        if( !empty($args['fields']) ) {
            $fields_arr = array();
            $fields_arr[] = (strpos($args['fields'], 'state_id') !== false) ? 'state_id' : '';
            $fields_arr[] = (strpos($args['fields'], 'state_name') !== false) ? 'state_name' : '';
            $fields_arr[] = (strpos($args['fields'], 'state_abr') !== false) ? 'state_abr' : '';
            $fields_arr[] = (strpos($args['fields'], 'state_slug') !== false) ? 'state_slug' : '';
            $fields_arr[] = (strpos($args['fields'], 'geojson') !== false) ? 'geojson' : '';
            $fields = implode(', ', array_filter($fields_arr));
        } else {
            $fields = 'state_id, state_name, state_abr, state_slug';
        }

        $sql = "SELECT $fields
            FROM states";

        if ( !empty($args['state']) ) {
            $where .= ($check ? " AND" : " WHERE") . " state_id = ?";
            $params[] = (int)trim( $args['state'] );
            $check = true;
        }

        if ( !empty($args['state_name']) ) {
            $where .= ($check ? " AND" : " WHERE") . " state_name = ?";
            $params[] = trim( $args['state_name'] );
            $check = true;
        }

        if ( !empty($args['state_%']) ) {
            $where .= ($check ? " AND" : " WHERE") . " MATCH (state_name) AGAINST (? IN BOOLEAN MODE) AND state_name LIKE ?";
            $params[] = '+'.str_replace(' ', ' +', trim( $args['state_%'] )).'*';
            $params[] = trim( $args['state_%'] ).'%';
            $check = true;
        }

        if ( !empty($args['s']) ) {
            $where .= ($check ? " AND" : " WHERE") . " state_name LIKE ?";
            $params[] = "%".trim($args['s'])."%";
            $check = true;
        }

        if ( !empty($args['state_abr']) ) {
            $where .= ($check ? " AND" : " WHERE") . " state_abr = ?";
            $params[] = trim( $args['state_abr'] );
            $check = true;
        }

        if ( !empty($args['orderby']) ) {
            if ($args['orderby'] == 'title') {
                $order = ' ORDER BY state_name';
            } else {
                $order = ' ORDER BY ' . esc_sql( $args['orderby'] );
            }
            $order .= ! empty( $args['order'] ) ? ' ' . esc_sql( $args['order'] ) : ' ASC';
        }

        if( !empty($args['paged']) && $args['per_page'] != -1 ) {
            $per_page = $args['per_page'];
            $page_number = $args['paged'];
            $limit = " LIMIT $per_page";
            $limit .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
        }

        $sql .= $where . $order . $limit;

        try {
            $sql = $this->db->prepare($sql);
            $sql->execute( $params );
            $result = $sql->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }

    }

    function get_counties( $args = array() ) {

        $check = false;
        $params = array();
        $fields = '';
        $where = '';
        $order = " ORDER BY sorce.county_name ASC";
        $limit = '';

        if( !empty($args['fields']) ) {
            $fields_arr = array();
            $fields_arr[] = (strpos($args['fields'], 'county_id') !== false) ? 'sorce.county_id' : '';
            $fields_arr[] = (strpos($args['fields'], 'county_name') !== false) ? 'sorce.county_name' : '';
            $fields_arr[] = (strpos($args['fields'], 'county_slug') !== false) ? 'sorce.county_slug' : '';
            $fields_arr[] = (strpos($args['fields'], 'state_id') !== false) ? 'key1.state_id' : '';
            $fields_arr[] = (strpos($args['fields'], 'state_name') !== false) ? 'key1.state_name' : '';
            $fields_arr[] = (strpos($args['fields'], 'state_abr') !== false) ? 'key1.state_abr' : '';
            $fields_arr[] = (strpos($args['fields'], 'geojson') !== false) ? 'sorce.geojson' : '';
            $fields = implode(', ', array_filter($fields_arr));
        } else {
            $fields = 'sorce.county_id, sorce.county_name, sorce.county_slug, key1.state_name, key1.state_abr, key1.state_slug, key1.state_id';
        }

        $sql = "SELECT $fields
            FROM counties sorce
            LEFT JOIN states key1 on key1.state_id = sorce.fk_state";

        if ( !empty($args['county']) ) {
            $where .= ($check ? " AND" : " WHERE") . " sorce.county_id = ?";
            $params[] = (int)trim( $args['county'] );
            $check = true;
        }

        if ( !empty($args['county_name']) ) {
            $where .= ($check ? " AND" : " WHERE") . " sorce.county_name = ?";
            $params[] = trim( $args['county_name'] );
            $check = true;
        }

        if ( !empty($args['s']) ) {
            $where .= ($check ? " AND" : " WHERE") . " sorce.county_name LIKE ?";
            $params[] = "%".trim($args['s'])."%";
            $check = true;
        }

        if ( !empty($args['county_%']) ) {
            $where .= ($check ? " AND" : " WHERE") . " MATCH (sorce.county_name) AGAINST (? IN BOOLEAN MODE) AND sorce.county_name LIKE ?";
            $params[] = '+'.str_replace(' ', ' +', trim( $args['county_%'] )).'*';
            $params[] = trim( $args['county_%'] ).'%';
            $check = true;
        }

        if ( !empty($args['counties']) ) {
            $counties_in = implode(', ', array_fill(0, count($args['counties']), '?'));
            $params = array_merge($params, $args['counties']);
            $where .= ($check ? " AND" : " WHERE") . " sorce.county_id IN ($counties_in)";
            $check = true;
        }

        if ( !empty($args['state']) ) {
            $where .= ($check ? " AND" : " WHERE") . " key1.state_id = ?";
            $params[] = (int)trim( $args['state'] );
            $check = true;
        }

        if ( !empty($args['state_slug']) ) {
            $where .= ($check ? " AND" : " WHERE") . " key1.state_slug = ?";
            $params[] = trim( $args['state_slug'] );
            $check = true;
        }

        if ( !empty($args['orderby']) ) {
            if ($args['orderby'] == 'title') {
                $order = ' ORDER BY sorce.county_name';
            } else {
                $order = ' ORDER BY ' . esc_sql( $args['orderby'] );
            }
            $order .= ! empty( $args['order'] ) ? ' ' . esc_sql( $args['order'] ) : ' ASC';
        }

        if( !empty($args['paged']) && $args['per_page'] != -1 ) {
            $per_page = $args['per_page'];
            $page_number = $args['paged'];
            $limit = " LIMIT $per_page";
            $limit .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
        }

        $sql .= $where . $order . $limit;

        try {
            $sql = $this->db->prepare($sql);
            $sql->execute( $params );
            $result = $sql->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }

    }

    function get_cities( $args = array() ) {

        $check = false;
        $params = array();
        $fields = '';
        $where = '';
        $order = " ORDER BY sorce.city_name ASC";
        $limit = '';

        if( !empty($args['fields']) ) {
            $fields_arr = array();
            $fields_arr[] = (strpos($args['fields'], 'city_id') !== false) ? 'sorce.city_id' : '';
            $fields_arr[] = (strpos($args['fields'], 'city_name') !== false) ? 'sorce.city_name' : '';
            $fields_arr[] = (strpos($args['fields'], 'city_slug') !== false) ? 'sorce.city_slug' : '';
            $fields_arr[] = (strpos($args['fields'], 'county_id') !== false) ? 'key2.county_id' : '';
            $fields_arr[] = (strpos($args['fields'], 'county_name') !== false) ? 'key2.county_name' : '';
            $fields_arr[] = (strpos($args['fields'], 'state_id') !== false) ? 'key1.state_id' : '';
            $fields_arr[] = (strpos($args['fields'], 'state_name') !== false) ? 'key1.state_name' : '';
            $fields_arr[] = (strpos($args['fields'], 'state_abr') !== false) ? 'key1.state_abr' : '';
            $fields_arr[] = (strpos($args['fields'], 'geojson') !== false) ? 'sorce.geojson' : '';
            $fields = implode(', ', array_filter($fields_arr));
        } else {
            $fields = 'sorce.city_id, sorce.city_name, sorce.city_slug, key1.state_name, key1.state_abr, key1.state_slug, key1.state_id, key2.county_id, key2.county_name';
        }

        $sql = "SELECT $fields
            FROM cities sorce
            LEFT JOIN states key1 on key1.state_id = sorce.fk_state
            LEFT JOIN counties key2 on key2.county_id = sorce.fk_county";

        if ( !empty($args['city']) ) {
            $where .= ($check ? " AND" : " WHERE") . " sorce.city_id = ?";
            $params[] = (int)trim( $args['city'] );
            $check = true;
        }

        if ( !empty($args['city_name']) ) {
            $where .= ($check ? " AND" : " WHERE") . " sorce.city_name = ?";
            $params[] = trim( $args['city_name'] );
            $check = true;
        }

        if ( !empty($args['city_%']) ) {
            $where .= ($check ? " AND" : " WHERE") . " MATCH (sorce.city_name) AGAINST (? IN BOOLEAN MODE) AND sorce.city_name LIKE ?";
            $params[] = '+'.str_replace(' ', ' +', trim( $args['city_%'] )).'*';
            $params[] = trim( $args['city_%'] ).'%';
            $check = true;
        }

        if ( !empty($args['cities']) ) {
            $cities_in = implode(', ', array_fill(0, count($args['cities']), '?'));
            $params = array_merge($params, $args['cities']);
            $where .= ($check ? " AND" : " WHERE") . " sorce.city_id IN ($cities_in)";
            $check = true;
        }

        if ( !empty($args['state']) ) {
            $where .= ($check ? " AND" : " WHERE") . " key1.state_id = ?";
            $params[] = (int)trim( $args['state'] );
            $check = true;
        }

        if ( !empty($args['state_slug']) ) {
            $where .= ($check ? " AND" : " WHERE") . " key1.state_slug = ?";
            $params[] = trim( $args['state_slug'] );
            $check = true;
        }

        if ( !empty($args['counties']) ) {
            $counties_in = implode(', ', array_fill(0, count($args['counties']), '?'));
            $params = array_merge($params, $args['counties']);
            $where .= ($check ? " AND" : " WHERE") . " sorce.fk_county IN ($counties_in)";
            $check = true;
        }

        if ( !empty($args['orderby']) ) {
            if ($args['orderby'] == 'title') {
                $order = ' ORDER BY sorce.city_name';
            } else {
                $order = ' ORDER BY ' . esc_sql( $args['orderby'] );
            }
            $order .= ! empty( $args['order'] ) ? ' ' . esc_sql( $args['order'] ) : ' ASC';
        }

        if( !empty($args['paged']) && $args['per_page'] != -1 ) {
            $per_page = $args['per_page'];
            $page_number = $args['paged'];
            $limit = " LIMIT $per_page";
            $limit .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
        }

        $sql .= $where . $order . $limit;

        try {
            $sql = $this->db->prepare($sql);
            $sql->execute( $params );
            $result = $sql->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    function get_neighborhoods( $args = array() ) {

        global $wpdb;
        $check = false;
        $params = array();
        $fields = '';
        $where = "";//" WHERE sorce.type LIKE 'STANDARD'";
        $order = " ORDER BY sorce.neighborhood_name ASC";
        $limit = '';
        // write_log('args'. print_r($args,1));

        if( !empty($args['fields']) ) {
            $fields_arr = array();
            $fields_arr[] = (strpos($args['fields'], 'neighborhood_id') !== false) ? 'sorce.neighborhood_id' : '';
            $fields_arr[] = (strpos($args['fields'], 'neighborhood_name') !== false) ? 'sorce.neighborhood_name' : '';
            $fields_arr[] = (strpos($args['fields'], 'neighborhood_slug') !== false) ? 'sorce.neighborhood_slug' : '';
            $fields_arr[] = (strpos($args['fields'], 'city_name') !== false) ? 'key1.city_name' : '';
            $fields = implode(', ', array_filter($fields_arr));
        } else {
            $fields = 'sorce.neighborhood_id, sorce.neighborhood_name, sorce.neighborhood_slug, key1.city_name, key1.city_slug, key1.city_id, key2.state_name, key2.state_slug, key2.state_id';
        }

        $sql = "SELECT $fields
            FROM neighborhoods sorce
            INNER JOIN cities key1 on key1.city_id = sorce.fk_city
            INNER JOIN states key2 on key2.state_id = key1.fk_state";

        if ( !empty($args['neighborhood']) ) {
            $where .= ($check ? " AND" : " WHERE") . " sorce.neighborhood_id = ?";
            $params[] = (int)trim( $args['neighborhood'] );
            $check = true;
        }

        if ( !empty($args['neighborhoods']) ) {
            $neighborhoods_in = implode(', ', array_fill(0, count($args['neighborhoods']), '?'));
            $params = array_merge($params, $args['neighborhoods']);
            $where .= ($check ? " AND" : " WHERE") . " sorce.neighborhood_id IN ($neighborhoods_in)";
            $check = true;
        }

        if ( !empty($args['neighborhood_%']) ) {
            $where .= ($check ? " AND" : " WHERE") . " MATCH (sorce.neighborhood_name) AGAINST (? IN BOOLEAN MODE) AND sorce.neighborhood_name LIKE ?";
            $params[] = '+'.str_replace(' ', ' +', trim( $args['neighborhood_%'] )).'*';
            $params[] = trim( $args['neighborhood_%'] ).'%';
            $check = true;
        }

        if ( !empty($args['city']) ) {
            $where .= ($check ? " AND" : " WHERE") . " sorce.fk_city = ?";
            $params[] = (int)trim( $args['city'] );
            $check = true;
        }

        if ( !empty($args['cities']) ) {
            $cities_in = implode(', ', array_fill(0, count($args['cities']), '?'));
            $params = array_merge($params, $args['cities']);
            $where .= ($check ? " AND" : " WHERE") . " sorce.fk_city IN ($cities_in)";
            $check = true;
        }

        if ( !empty($args['orderby']) ) {
            if ($args['orderby'] == 'title') {
                $order = ' ORDER BY sorce.neighborhood_name';
            } else {
                $order = ' ORDER BY ' . esc_sql( $args['orderby'] );
            }
            $order .= ! empty( $args['order'] ) ? ' ' . esc_sql( $args['order'] ) : ' ASC';
        }

        if( !empty($args['paged']) && $args['per_page'] != -1 ) {
            $per_page = $args['per_page'];
            $page_number = $args['paged'];
            $limit = " LIMIT $per_page";
            $limit .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
        }

        $sql .= $where . $order . $limit;

        try {
            $sql = $this->db->prepare($sql);
            $sql->execute( $params );
            $result = $sql->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    function get_postal( $args = array() ) {
        $params = array();
        $fields = '';
        $check = false;
        $where = "";//" WHERE sorce.type LIKE 'STANDARD'";
        $order = " ORDER BY key3.city_name ASC";
        $limit = '';

        if( !empty($args['fields']) ) {
            $fields_arr = array();
            $fields_arr[] = (strpos($args['fields'], 'postal_id') !== false) ? 'sorce.postal_id' : '';
            $fields_arr[] = (strpos($args['fields'], 'postal_code') !== false) ? 'sorce.postal_code' : '';
            $fields_arr[] = (strpos($args['fields'], 'state_id') !== false) ? 'key1.state_id' : '';
            $fields_arr[] = (strpos($args['fields'], 'state_name') !== false) ? 'key1.state_name' : '';
            $fields_arr[] = (strpos($args['fields'], 'state_abr') !== false) ? 'key1.state_abr' : '';
            $fields_arr[] = (strpos($args['fields'], 'county_id') !== false) ? 'key2.county_id' : '';
            $fields_arr[] = (strpos($args['fields'], 'county_name') !== false) ? 'key2.county_name' : '';
            $fields_arr[] = (strpos($args['fields'], 'city_id') !== false) ? 'key3.city_id' : '';
            $fields_arr[] = (strpos($args['fields'], 'city_name') !== false) ? 'key3.city_name' : '';
            $fields_arr[] = (strpos($args['fields'], 'neighborhood_id') !== false) ? 'key4.neighborhood_id' : '';
            $fields_arr[] = (strpos($args['fields'], 'neighborhood_name') !== false) ? 'key4.neighborhood_name' : '';
            $fields_arr[] = (strpos($args['fields'], 'geojson') !== false) ? 'sorce.geojson' : '';
            $fields = implode(', ', array_filter($fields_arr));
        } else {
            $fields = 'sorce.postal_id, sorce.postal_code, key1.state_name, key1.state_abr, key1.state_slug, key1.state_id, key2.county_name, key2.county_slug, key2.county_id, key3.city_name, key3.city_slug, key3.city_id, key4.neighborhood_name, key4.neighborhood_id';
        }

        $sql = "SELECT $fields
            FROM postalcodes sorce
            LEFT JOIN states key1 on key1.state_id = sorce.fk_state
            LEFT JOIN counties key2 on key2.county_id = sorce.fk_county
            LEFT JOIN cities key3 on key3.city_id = sorce.fk_main_city
            LEFT JOIN neighborhoods key4 on key4.neighborhood_id = sorce.fk_neighborhood";

        if ( !empty($args['postal_id']) ) {
            $where .= ($check ? " AND" : " WHERE") . " sorce.postal_id = ?";
            $params[] = (int)trim( $args['postal_id'] );
            $check = true;
        }

        if ( !empty($args['postal_code']) ) {
            $where .= ($check ? " AND" : " WHERE") . " sorce.postal_code = ?";
            $params[] = trim( $args['postal_code'] );
            $check = true;
        }

        if ( !empty($args['postal_%']) ) {
            // $where .= ($check ? " AND" : " WHERE") . " sorce.postal_code LIKE ?";
            $where .= ($check ? " AND" : " WHERE") . " MATCH (sorce.postal_code) AGAINST (? IN BOOLEAN MODE)";
            $params[] = trim( $args['postal_%'] ).'*';
            // $params[] = trim( $args['postal_%'] )."%";
            $check = true;
        }

        if ( !empty($args['postal_codes']) ) {
            $postal_codes_in = implode(', ', array_fill(0, count($args['postal_codes']), '?'));
            $params = array_merge($params, $args['postal_codes']);
            $where .= ($check ? " AND" : " WHERE") . " sorce.postal_code IN ($postal_codes_in)";
            $check = true;
        }


        if ( !empty($args['city']) ) {
            $where .= ($check ? " AND" : " WHERE") . " sorce.fk_main_city = ?";
            $params[] = (int)trim( $args['city'] );
            $check = true;
        }
        if ( !empty($args['cities']) ) {
            $cities_in = implode(', ', array_fill(0, count($args['cities']), '?'));
            $params = array_merge($params, $args['cities']);
            $where .= ($check ? " AND" : " WHERE") . " sorce.fk_main_city IN ($cities_in)";
            $check = true;
        }

        if ( !empty($args['state']) ) {
            $where .= ($check ? " AND" : " WHERE") . " sorce.fk_state = ?";
            $params[] = (int)trim( $args['state'] );
            $check = true;
        }

        if ( !empty($args['county']) ) {
            $where .= ($check ? " AND" : " WHERE") . " sorce.fk_county = ?";
            $params[] = (int)trim( $args['county'] );
            $check = true;
        }

        if ( !empty($args['orderby']) ) {
            if ($args['orderby'] == 'title') {
                $order = ' ORDER BY sorce.postal_code';
            } else {
                $order = ' ORDER BY ' . esc_sql( $args['orderby'] );
            }
            $order .= ! empty( $args['order'] ) ? ' ' . esc_sql( $args['order'] ) : ' ASC';
        }

        if( !empty($args['paged']) && $args['per_page'] != -1 ) {
            $per_page = $args['per_page'];
            $page_number = $args['paged'];
            $limit = " LIMIT $per_page";
            $limit .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
        }

        $sql .= $where . $order . $limit;

        try {
            $sql = $this->db->prepare($sql);
            $sql->execute( $params );
            $result = $sql->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    function find_locartions( $query ) {
        $params = array();

        //state
        $sql = "(";
        $sql .= "SELECT state_id, state_name, state_abr, NULL as 'county_id', NULL as 'county_name', NULL as 'city_id', NULL as 'city_name'
            FROM states
            WHERE MATCH (state_name) AGAINST (? IN BOOLEAN MODE) AND state_name LIKE ?
            LIMIT 3";
            $params[] = '+'.str_replace(' ', ' +', trim( $query )).'*';
            $params[] = trim( $query ).'%';
        $sql .= ")";

        $sql .= " UNION ALL ";

        //county
        $sql .= " (";
        $sql .= "SELECT state_id, state_name, state_abr, county_id, county_name, NULL as 'city_id', NULL as 'city_name'
            FROM counties sorce
            LEFT JOIN states key1 on key1.state_id = sorce.fk_state
            WHERE MATCH (sorce.county_name) AGAINST (? IN BOOLEAN MODE) AND sorce.county_name LIKE ?
            LIMIT 5";
            $params[] = '+'.str_replace(' ', ' +', trim( $query )).'*';
            $params[] = trim( $query ).'%';
        $sql .= ")";

        $sql .= " UNION ALL ";

        //city
        $sql .= " (";
        $sql .= "SELECT state_id, state_name, state_abr, county_id, county_name, city_id, city_name
            FROM cities sorce
            LEFT JOIN states key1 on key1.state_id = sorce.fk_state
            LEFT JOIN counties key2 on key2.county_id = sorce.fk_county
            WHERE MATCH (sorce.city_name) AGAINST (? IN BOOLEAN MODE) AND sorce.city_name LIKE ?
            LIMIT 5";
            $params[] = '+'.str_replace(' ', ' +', trim( $query )).'*';
            $params[] = trim( $query ).'%';
        $sql .= ")";

        try {
            $sql = $this->db->prepare($sql);
            $sql->execute( $params );
            $result = $sql->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function insert(Array $input)
    {
        $statement = "
            INSERT INTO person
                (firstname, lastname, firstparent_id, secondparent_id)
            VALUES
                (:firstname, :lastname, :firstparent_id, :secondparent_id);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'firstname' => $input['firstname'],
                'lastname'  => $input['lastname'],
                'firstparent_id' => $input['firstparent_id'] ?? null,
                'secondparent_id' => $input['secondparent_id'] ?? null,
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update($id, Array $input)
    {
        $statement = "
            UPDATE person
            SET
                firstname = :firstname,
                lastname  = :lastname,
                firstparent_id = :firstparent_id,
                secondparent_id = :secondparent_id
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int) $id,
                'firstname' => $input['firstname'],
                'lastname'  => $input['lastname'],
                'firstparent_id' => $input['firstparent_id'] ?? null,
                'secondparent_id' => $input['secondparent_id'] ?? null,
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function delete($id)
    {
        $statement = "
            DELETE FROM person
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('id' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}
