CREATE OR REPLACE FUNCTION get_children(
    IN in_id INTEGER
)
RETURNS TABLE (
    result_id INTEGER,
    result_parent_id INTEGER,
    result_type_name VARCHAR(255),
    result_enabled BOOLEAN
)
AS $$
BEGIN
    RETURN QUERY
    WITH RECURSIVE children_hierarchy AS (
        SELECT
            ag.id::INTEGER AS result_id,
            ag.parent_id::INTEGER AS result_parent_id,
            at.name::VARCHAR(255) AS result_type_name,
            ag.enabled::BOOLEAN AS result_enabled
        FROM
            auth_groups ag
        JOIN auth_types at ON ag.type_id = at.id
        WHERE
            ag.parent_id = in_id

        UNION ALL

        SELECT
            ag.id::INTEGER,
            ag.parent_id::INTEGER,
            at.name::VARCHAR(255),
            ag.enabled::BOOLEAN
        FROM
            auth_groups ag
        JOIN auth_types at ON ag.type_id = at.id
        JOIN children_hierarchy ch ON ag.id = ch.result_parent_id
    )
    SELECT * FROM children_hierarchy LIMIT 1000;

END;
$$ LANGUAGE plpgsql;

