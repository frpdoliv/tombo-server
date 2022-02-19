USE tombo;

DROP PROCEDURE IF EXISTS CheckLocationOwnership;
CREATE PROCEDURE CheckLocationOwnership(IN book_owner_id BIGINT, IN location_id BIGINT)
BEGIN
    IF NOT EXISTS(SELECT id FROM locations WHERE id=location_id AND user_id=book_owner_id) THEN
        SIGNAL SQLSTATE '02000';
    END IF;
END;

DROP TRIGGER IF EXISTS check_location_ownership_on_insert_book;
CREATE TRIGGER check_location_ownership_on_insert_book BEFORE INSERT ON books
    FOR EACH ROW CALL CheckLocationOwnership(NEW.user_id, NEW.location_id);

DROP TRIGGER IF EXISTS check_location_ownership_on_update_book;
CREATE TRIGGER check_location_ownership_on_update_book BEFORE UPDATE ON books
    FOR EACH ROW CALL CheckLocationOwnership(NEW.user_id, NEW.location_id);
