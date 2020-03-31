TARGET = audience1st-ticket-availability
ZIP = zip

PLUGIN_FILES = audience1st_ticket_availability.php audience1st_ticket_availability_menu.php style.css uninstall.php readme.txt
ASSETS = $(wildcard assets/*)

all: $(TARGET).zip

.PHONY: release
release:
	@[ "${RELEASEDIR}" ] || ( echo "Must set RELEASEDIR" ; exit 1 )
	cp $(PLUGIN_FILES) $(RELEASEDIR)/trunk
	cp $(ASSETS) $(RELEASEDIR)/assets

$(TARGET).zip: audience1st_ticket_availability.php audience1st_ticket_availability_menu.php style.css uninstall.php readme.txt
	$(ZIP) -u $(TARGET).zip $^



