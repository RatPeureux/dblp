<?php

function type_shorten($type) {
  if ($type == "Books and Theses") {
    return 'Thèse / livre';
  } else if ($type == "Conference and Workshop Papers") {
    return 'Conférence';
  } else if ($type == "Journal Articles") {
    return 'Revue';
  } else if ($type == "Editorship") {
    return 'Ouvrage collectif';
  } else {
    return 'Autre publication';
  }
}

function type_to_logo($type) {
  if ($type == "Books and Theses") {
    return '<i class="fa-solid fa-book"></i>';
  } else if ($type == "Conference and Workshop Papers") {
    return '<i class="fa-solid fa-people-line"></i>';
  } else if ($type == "Journal Articles") {
    return '<i class="fa-solid fa-newspaper"></i>';
  } else if ($type == "Editorship") {
    return '<i class="fa-solid fa-pen"></i>';
  } else {  
    return '<i class="fa-solid fa-globe"></i>';
  }
}

