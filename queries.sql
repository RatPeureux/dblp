select country, count(*) as nombre_villes_par_pays
from _ville
group by country;

select continent, count(distinct country) as nombre_pays
from _pays_continent
group by continent;

select pc.continent, count(v.id) as nombre_villes_par_continent
from _ville v
join _pays_continent pc on v.country = pc.country
group by pc.continent;

-- nombre maximum de villes par pays

-- nombre moyen de villes par continent

select continent, count(country) as max_pays_par_continent
from _pays_continent
group by continent
order by max_pays desc
limit 1;

-- importer les pays / continents de puis csv
copy t (x1, ... , x10)
from '/path/to/my_file'
with (format csv);

-- importer les villes dans le monde depuis le csv
copy _ville (city, city_ascii, lat, lng, country, iso2, iso3, admin_name, capital, population, id)
from '/files/worldcities.csv'
with (format csv, header);
