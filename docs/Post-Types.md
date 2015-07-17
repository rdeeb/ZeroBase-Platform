# Post Types

With the addition of yaml loaders to the Zerobase Platform, adding new post types to any Wordpress site is a breeze, you will just need to write down some configuration in the required file and Zerobase will create the code for you.

## How to create a post type without coding

First of all you we are assuming that you followed the basic instalation and you have told Zerobase to load your module. After this is done, Zerobase will search for *.post_type.yml files in your module path. This let you have multiple files that defines post types, or just one file, the decision on going with multiple files or just one will come on how you want to organize your post types.

### Anatomy of post_type.yml file

This files consiste of a simple definition of key-value pairs, at the begining of the file you will define your post type id, this is how Wordpress will call your post type internally, this id can not have spaces, or exceed 20 characters. After that you will se a labels section, and an arguments section, here you will define your post type.

Study the following example to learn about each possible key-value pairs:

```yaml
#This is the id of the post type
test_post_type:
  #This labels change the text in the front end, this is optional
  labels:
    #The name of your post type
    name:               Projects
    #The singular version of your name
    singular_name:      Project
    #From now one this are kinda self explanatory
    add_new:            Add New
    add_new_item:       Add New Project
    edit_item:          Edit Project
    new_item:           New Project
    all_items:          All Projects
    view_item:          View Project
    search_items:       Search for Projects
    not_found:          No projects where found
    not_found_in_trash: No projects where found in the trash bin
    parent_item_colon:  Parent project:
    menu_name:          Projects
  arguments:
    #The description of your post types
    description:        Projects I've worked on
    #If this post type should shown to authors and readers
    public:             true
    #The menu position of this post type, 5 is right bellow the WP Posts
    menu_position:      5
    #Supports is what "default fields" will this post type have
    supports:
      - title
      - editor
      - thumbnail
      - comments
    #Should we get an archive page for this post type
    has_archive:        true
    #The icon on the admin side bar
    menu_icon:          dashicons-location-alt
    #The rewrite rule for the url
    rewrite:
      #This allow us to have /projects/{permalink} structure in our urls
      slug:             projects
      #This shows the archive page when going to /projects
      with_front:       true
```

For a complete list of posible keys you can use, refer to the WP codex page on [registering post types](https://codex.wordpress.org/Function_Reference/register_post_type)
