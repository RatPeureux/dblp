from scholarly import scholarly

# Recherche un chercheur par nom
search_query = scholarly.search_author("delhay arnaud")
author = next(search_query)
print(author)

search_query = scholarly.search_author("d'orazio laurent")
author = next(search_query)
print(author)
