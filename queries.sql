SELECT country, COUNT(*) AS nombre_villes_par_pays
FROM _ville
GROUP BY country;

SELECT continent, COUNT(DISTINCT country) AS nombre_pays
FROM _pays_continent
GROUP BY continent;

SELECT pc.continent, COUNT(v.id) AS nombre_villes_par_continent
FROM _ville v
JOIN _pays_continent pc ON v.country = pc.country
GROUP BY pc.continent;

-- Nombre maximum de villes par pays

-- Nombre moyen de villes par continent

SELECT continent, COUNT(country) AS max_pays_par_continent
FROM _pays_continent
GROUP BY continent
ORDER BY max_pays DESC
LIMIT 1;

-- Importer les pays / continents de puis CSV
copy t (x1, ... , x10)
from '/path/to/my_file'
with (format csv)

-- Importer les villes dans le monde depuis le CSV
copy _ville (city, city_ascii, lat, lng, country, iso2, iso3, admin_name, capital, population, id)
from '/files/worldcities.csv'
with (format csv, HEADER)

