TARGET = audience1st-ticket-availability
ZIP = zip

all: $(TARGET).zip

$(TARGET).zip: audience1st_ticket_availability.php audience1st_ticket_availability_menu.php style.css uninstall.php readme.txt
	$(ZIP) -u $(TARGET).zip $^



