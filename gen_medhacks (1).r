
args <- commandArgs(trailingOnly = True)
filepath <- args[1]
library(data.table)
setwd(filepath)
#Set working directory to the one specified by the user, which contains necessary files

#Setting up fastq files for alignment mapping
system("cd normal")                              
system("cat file*.fastq > normal.fastq")
system("cd mars")
system("cat file*.fastq > mars.fastq")
mars_read = "~/med_hacks/normal/normal.fastq"
normal_read = "~/med_hacks/mars/mars.fastq"
normal = "~/med_hacks/normal_bam"
mars = "~/med_hacks/tumor_bam"
ref = "~/med_hacks/ref.fa"

#Run BWAMem to align reads
system(sprintf("bwa mem %s %s > %s", ref, mars_read, mars))   #must have already downloaded BWAmem in this directory and created a reference file
system(sprintf("bwa mem %s %s > %s", ref, normal_read, normal))  

#Run Strelka to detect variants
system("cd ~/med_hacks/strelka_workflow-1.0.15")  #must have already downloaded and installed strelka in this folder
system("./configure --prefix=/path/to/install/tomake")
system("STRELKA_INSTALL_DIR=~/med_hacks/strelka_workflow-1.0.15")
system("WORK_DIR=~/med_hacks")
system("cd $WORK_DIR")
system("cp $STRELKA_INSTALL_DIR/etc/strelka_config_eland_default.ini config.ini")
system(sprintf("$STRELKA_INSTALL_DIR/bin/configureStrelkaWorkflow.pl --normal=%s --tumor=%s --ref=%s --config=config.ini --output-dir=./myAnalysis",normal,mars,ref))


#See if mutations are important!

cancer_genes = read.csv("cancer_genes.csv")


#Call the outputs of Strelka - variant files
snv_path ="all.somatic.snvs.vcf"
indel_path = "all.somatic.indels.vcf"

#Read the files
snvs = read.table(snv_path, sep="\t", skip = which.max(count.fields(snv_path) == 11)+8, header=FALSE)
indels = read.table(indel_path, sep="\t", skip = which.max(count.fields(indel_path) == 11)+8, header=FALSE)

snvs  #single nucleotide variants

indels  #insertions and deletions

#See if SNVS are in cancer genes
index <- c()
index2 <- c()

snvs_on_mars <- function(snvs,cancer_genes){
    for (i in 1:dim(snvs)[1]){
        for (j in 1:589){
            chrom_num = substr(cancer_genes$Genome.Location[j], 1, regexpr(':', cancer_genes$Genome.Location[j])-1)
            chrom_beg = substr(cancer_genes$Genome.Location[j], regexpr(':', cancer_genes$Genome.Location[j])+1, regexpr('-', cancer_genes$Genome.Location[j])-1)
            char = as.character(cancer_genes$Genome.Location[j])
            chrom_end = substr(cancer_genes$Genome.Location[j],regexpr('-', cancer_genes$Genome.Location[j])+1,nchar(char))
            if (snvs$V1[i] == chrom_num){if((snvs$V2[i] >= chrom_beg)&(snvs$V2[i] <= chrom_end)){
                index = c(index,j)
                index2 = c(index2,i)}}}}
return(list(index,index2))}                                

#See if Indels are in cancer genes
index3 <- c()
index4 <- c()

indels_on_mars <- function(indels,cancer_genes){
    for (i in 1:dim(indels)[1]){
        for (j in 1:589){
            chrom_num = substr(cancer_genes$Genome.Location[j], 1, regexpr(':', cancer_genes$Genome.Location[j])-1)
            chrom_beg = substr(cancer_genes$Genome.Location[j], regexpr(':', cancer_genes$Genome.Location[j])+1, regexpr('-', cancer_genes$Genome.Location[j])-1)
            char = as.character(cancer_genes$Genome.Location[j])
            chrom_end = substr(cancer_genes$Genome.Location[j],regexpr('-', cancer_genes$Genome.Location[j])+1,nchar(char))
            if (indels$V1[i] == chrom_num){if ((indels$V2[i] >= chrom_beg)&(indels$V2[i] <= chrom_end)){
                index3 = c(index3,j)
                index4 = c(index4,i)}}}}
return(list(index3,index4))}

#Take outputs of functions
index = snvs_on_mars(snvs, cancer_genes)
index2 = indels_on_mars(indels, cancer_genes)

indexa = index[[1]]
indexb = index[[2]]
indexc = index2[[1]]
indexd = index2[[2]]

#set up data frame with results
Gene_Symbol <- c()
Qual <- c()

for(i in indexa){Gene_Symbol <- paste(Gene_Symbol,cancer_genes$Gene.Symbol[i],sep =" ")}
for(i in indexb){Qual <- c(Qual, (substr(snvs$V8[i], regexpr('QSS=', snvs$V8[i])+4, regexpr('QSS_NT',snvs$V8[i])-2)))}
for(j in indexc){Gene_Symbol <- paste(Gene_Symbol,cancer_genes$Gene.Symbol[j], sep=" ")}
for(j in indexd){Qual <- c(Qual, substr(indels$V8[j], regexpr('QSI=', indels$V8[j])+4, regexpr('QSI_NT',indels$V8[j])-2))}

Gene_Sym <- strsplit(Gene_Symbol, split = " ")
Gene_Sym <- (Gene_Sym[[1]])
Gene_Sym <- Gene_Sym[2:56]

df = data.frame(Gene_Sym, Qual)
df   #data frame of the Gene_Symbol and the Quality Score 

#Use literature searching to find necessary probability estimates for solving bayes theorem
bayes = read.csv("bayes.csv")
bayes = bayes[2]

#Make arrays of all the probabilities for each mutation
levels(df$Gene_Sym)<- c('BRD4','CAMTA1','CARD11','CIITA','CUX1','DDX10','DNM2','ERC1','ETV1','FGFR2','KDM5A','KRAS','MLH1','MSH2','MSH6','MTOR','NCOR1','NFATC2','PMS1','RSPO2','SMARCA4','SPEN','SRGAP3','STK11','TP53','YWHAE','ABL1','BRAF','BRCA1','CCND1','CDX2','EGFR','ESR1','EZH2','FGFR1','HRAS','KDR','MALT1','MET','MYC','NF1','NKX2-1','PDGFRA','PTEN','RB1','RET','SMAD4')
levels(bayes$Gene_Name)<- c('BRD4','CAMTA1','CARD11','CIITA','CUX1','DDX10','DNM2','ERC1','ETV1','FGFR2','KDM5A','KRAS','MLH1','MSH2','MSH6','MTOR','NCOR1','NFATC2','PMS1','RSPO2','SMARCA4','SPEN','SRGAP3','STK11','TP53','YWHAE','ABL1','BRAF','BRCA1','CCND1','CDX2','EGFR','ESR1','EZH2','FGFR1','HRAS','KDR','MALT1','MET','MYC','NF1','NKX2-1','PDGFRA','PTEN','RB1','RET','SMAD4')
prob_a <- c()
prob_b <- c()
prob_a_b <- c()
blurb <- c()
for(i in 1:55)
{
    for(j in 1:28){
        if(df$Gene_Sym[i] == bayes$Gene_Name[j]){prob_a_b = c(prob_a_b,bayes$Cancer_With_Mutant[j])
                                                prob_b = c(prob_b,bayes$Cancer_Incidence[j])
                                                prob_a = c(prob_a,df$Qual[i])
                                                blurb = paste(blurb, bayes$Blurb[i], sep = ":")}
    }}
    


prob_a <- 10^(-prob_a/10)
prob_b_a <- (prob_a_b*prob_b)/prob_a  #Calculate the desired probability

df$prob<- prob_b_a #add to data.frame

blurb<-strsplit(blurb, split = ":")

blurb = blurb[[1]] 
#length(blurb)

blurb=blurb[2:56]

df$Blurb <- blurb

df[1:27,] #final output - gene that is mutated, quality score, probabiltiy that has the cancer, info about the cancer

write.table(df[1:27,], file = 'gene_freq.csv')

#write.table(df[1:27,1:3], file = 'gene_freq1.csv')


