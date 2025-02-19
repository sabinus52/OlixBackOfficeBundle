#---
#--- Commandes des assets du bundle
#--- ————————————————————————————————————————————————————————————————————————————————————————————————
##
## Options disponibles :
##


help: Makefile ## Affiche cette aide
	@sed -n 's/^#---//p' $<
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' Makefile | awk 'BEGIN {FS = ":.*?## "}{printf "  \033[32m%-20s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m\x08\x08\x08/'



publish: ## Publication du package sur NPM
	@npm publish

##