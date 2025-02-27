# Importer les pays / continents de puis CSV
copy t (x1, ... , x10)
from '/path/to/my_file'
with (format csv)

# Importer les villes dans le monde depuis le CSV
copy _ville (city, city_ascii, lat, lng, country, iso2, iso3, admin_name, capital, population, id)
from '/files/worldcities.csv'
with (format csv, HEADER)

