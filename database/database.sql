alter table nodes add tsv tsvector

CREATE FUNCTION nodes_update_trigger() RETURNS trigger AS $$
declare item varchar;
begin
  new.tsv :=
     setweight(to_tsvector('pg_catalog.simple', coalesce(new.title,'')), 'A') ||
     setweight(to_tsvector('pg_catalog.simple', coalesce(new.street_number,'')), 'D') ||
     setweight(to_tsvector('pg_catalog.simple', coalesce(new.address,'')), 'A') ||
     setweight(to_tsvector('pg_catalog.simple', coalesce((select name from cities where id = new.city_id),'')), 'B') ||
     setweight(to_tsvector('pg_catalog.simple', coalesce((select name from countries where id = new.country_id),'')), 'B');
     
     create temporary table tab(tsv tsvector);
     insert into tab(tsv) values(new.tsv);
     for item in (select word from ts_stat('select tsv from tab')) loop
     	if not exists (select * from words where words.name = item) then
        	insert into words(name) values(item);
        end if;
     end loop;
     drop table tab;
  return new;
end
$$ LANGUAGE plpgsql;

CREATE TRIGGER nodes_update BEFORE INSERT OR UPDATE
    ON nodes FOR EACH ROW EXECUTE PROCEDURE nodes_update_trigger();


CREATE INDEX nodes_index ON nodes USING GIN (tsv);